<?php
declare(strict_types=1);

/**
 * Autentificare, limitare de rata si tokenuri CSRF.
 *
 * Exista un singur utilizator real — clienta. Nu exista inregistrare publica,
 * nu exista recuperare de parola prin email (o adresa de email compromisa nu
 * trebuie sa deschida panoul care contine mesaje cu date de sanatate).
 * Resetarea se face din linia de comanda; procedura e in INSTALARE.md.
 */
final class Auth
{
    // -----------------------------------------------------------------------
    // Sesiune
    // -----------------------------------------------------------------------

    public static function utilizator(): ?array
    {
        $id = $_SESSION['utilizator_id'] ?? null;
        if ($id === null) {
            return null;
        }

        static $cache = null;
        if ($cache === null) {
            $cache = Database::one('SELECT id, email, nume, rol, psiholog_id FROM utilizatori WHERE id = ?', [$id]);
            // Utilizator sters in timp ce sesiunea era activa.
            if ($cache === null) {
                self::iesire();
            }
        }
        return $cache;
    }

    public static function esteAutentificat(): bool
    {
        return self::utilizator() !== null;
    }

    /** Opreste executia daca vizitatorul nu e autentificat. */
    public static function cere(): void
    {
        if (!self::esteAutentificat()) {
            redirect('/admin/autentificare');
        }
    }

    /**
     * Verifica datele si porneste sesiunea.
     * Returneaza null la reusita, sau mesajul de eroare de afisat.
     */
    public static function autentifica(string $email, string $parola): ?string
    {
        $ip = self::ipBinar();

        if (self::esteBlocat($ip, $email)) {
            $minute = (int) ceil((int) config('securitate', 'durata_blocare') / 60);
            return "Prea multe încercări. Mai încearcă peste {$minute} de minute.";
        }

        $utilizator = Database::one('SELECT * FROM utilizatori WHERE email = ?', [$email]);

        // password_verify pe un hash inventat cand utilizatorul nu exista:
        // fara asta, un email inexistent raspunde vizibil mai repede decat unul
        // existent, iar diferenta spune atacatorului ce adrese sunt valide.
        $hash = $utilizator['parola_hash']
            ?? '$2y$12$invaliddummyhashinvaliddummyhashinvaliddummyhashinvaliddu';

        $corect = password_verify($parola, $hash) && $utilizator !== null;

        self::inregistreazaIncercare($ip, $email, $corect);

        if (!$corect) {
            return 'Email sau parolă greșite.';
        }

        // Rehash daca algoritmul implicit al PHP s-a schimbat de la ultima logare.
        if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            Database::run(
                'UPDATE utilizatori SET parola_hash = ? WHERE id = ?',
                [password_hash($parola, PASSWORD_DEFAULT), $utilizator['id']]
            );
        }

        // Id nou de sesiune dupa autentificare: altfel un id obtinut inainte de
        // logare ramane valid si dupa (session fixation).
        session_regenerate_id(true);
        $_SESSION['utilizator_id'] = (int) $utilizator['id'];
        $_SESSION['autentificat_la'] = time();

        Database::run('UPDATE utilizatori SET ultima_autentificare = NOW() WHERE id = ?', [$utilizator['id']]);
        Database::run('DELETE FROM login_attempts WHERE email = ?', [$email]);

        return null;
    }

    public static function iesire(): never
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        redirect('/admin/autentificare');
    }

    // -----------------------------------------------------------------------
    // Limitare de rata
    // -----------------------------------------------------------------------

    /**
     * Blocam pe doua chei deodata: pe IP si pe adresa de email.
     * Doar pe IP nu ajunge — un atac distribuit ocoleste banal limita. Doar pe
     * email nu ajunge nici el, pentru ca permite baleierea mai multor adrese.
     */
    private static function esteBlocat(string $ip, string $email): bool
    {
        $maxim  = (int) config('securitate', 'max_incercari');
        $durata = (int) config('securitate', 'durata_blocare');

        $dupaIp = (int) Database::value(
            'SELECT COUNT(*) FROM login_attempts
             WHERE ip = ? AND reusita = 0 AND incercat_la > NOW() - INTERVAL ? SECOND',
            [$ip, $durata]
        );
        if ($dupaIp >= $maxim) {
            return true;
        }

        $dupaEmail = (int) Database::value(
            'SELECT COUNT(*) FROM login_attempts
             WHERE email = ? AND reusita = 0 AND incercat_la > NOW() - INTERVAL ? SECOND',
            [$email, $durata]
        );

        return $dupaEmail >= $maxim;
    }

    private static function inregistreazaIncercare(string $ip, string $email, bool $reusita): void
    {
        Database::run(
            'INSERT INTO login_attempts (ip, email, reusita) VALUES (?, ?, ?)',
            [$ip, $email, $reusita ? 1 : 0]
        );

        // Curatare oportunista: pastram tabela mica fara sa avem nevoie de cron.
        if (random_int(1, 50) === 1) {
            Database::run('DELETE FROM login_attempts WHERE incercat_la < NOW() - INTERVAL 1 DAY');
        }
    }

    /** IP-ul in forma binara: incape si IPv6, si ocupa mai putin. */
    private static function ipBinar(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $binar = @inet_pton($ip);
        return $binar === false ? inet_pton('0.0.0.0') : $binar;
    }

    // -----------------------------------------------------------------------
    // CSRF
    //
    // Fiecare formular care schimba stare poarta un token. Fara el, o pagina
    // straina poate trimite cereri in numele cuiva autentificat.
    // -----------------------------------------------------------------------

    public static function tokenCsrf(): string
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    /** Campul ascuns, gata de pus in formular. */
    public static function campCsrf(): string
    {
        return '<input type="hidden" name="csrf" value="' . e(self::tokenCsrf()) . '">';
    }

    /** Opreste cererea daca tokenul lipseste sau nu se potriveste. */
    public static function cereCsrf(): void
    {
        $primit  = (string) ($_POST['csrf'] ?? '');
        $asteptat = (string) ($_SESSION['csrf'] ?? '');

        // hash_equals compara in timp constant.
        if ($asteptat === '' || !hash_equals($asteptat, $primit)) {
            http_response_code(419);
            exit('Sesiunea a expirat. Întoarce-te, reîncarcă pagina și încearcă din nou.');
        }
    }
}
