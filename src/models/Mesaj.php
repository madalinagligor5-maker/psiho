<?php
declare(strict_types=1);

/**
 * Mesajele din formularul de contact.
 *
 * Continutul e criptat la rest cu AES-256-GCM. Motivul nu e paranoia: un mesaj
 * de pe acest site poate contine date despre sanatatea cuiva, care intra la
 * categorie speciala in sensul Art. 9 GDPR. Un dump de baza de date ajuns unde
 * nu trebuie — backup pierdut, gazduire compromisa — nu trebuie sa fie citibil.
 *
 * Cheia sta in config/config.php, care nu ajunge in git si e exclusa explicit
 * din deploy.sh. Daca se pierde cheia, mesajele deja criptate devin definitiv
 * necitibile; INSTALARE.md spune asta apasat.
 *
 * De ce GCM si nu CBC: GCM autentifica textul cifrat. Un rand modificat in
 * baza de date esueaza la decriptare in loc sa produca gunoi plauzibil.
 */
final class Mesaj
{
    private const CIFRU = 'aes-256-gcm';

    /** Situatiile din dropdown-ul formularului. Scurte, deliberat. */
    public const SITUATII = [
        'primul-pas'   => 'E prima dată când caut un psiholog',
        'am-mai-fost'  => 'Am mai făcut terapie înainte',
        'de-mult-timp' => 'Mă gândesc de mult timp la asta',
        'nu-stiu'      => 'Nu știu încă',
    ];

    // -----------------------------------------------------------------------
    // Criptare
    // -----------------------------------------------------------------------

    private static function cheie(): string
    {
        $hex = (string) config('securitate', 'cheie_criptare');
        if (strlen($hex) !== 64 || !ctype_xdigit($hex)) {
            throw new RuntimeException(
                'cheie_criptare lipsește sau nu are 64 de caractere hex. Vezi INSTALARE.md.'
            );
        }
        return (string) hex2bin($hex);
    }

    /** Returneaza base64(iv | tag | textCifrat). */
    public static function cripteaza(string $text): string
    {
        $iv  = random_bytes(12);           // 12 octeti: lungimea recomandata pentru GCM
        $tag = '';
        $cifrat = openssl_encrypt($text, self::CIFRU, self::cheie(), OPENSSL_RAW_DATA, $iv, $tag);

        if ($cifrat === false) {
            throw new RuntimeException('Criptare eșuată.');
        }
        return base64_encode($iv . $tag . $cifrat);
    }

    /** Null daca textul a fost modificat sau cheia e alta. */
    public static function decripteaza(?string $pachet): ?string
    {
        if ($pachet === null || $pachet === '') {
            return null;
        }
        $brut = base64_decode($pachet, true);
        if ($brut === false || strlen($brut) < 29) {   // 12 iv + 16 tag + minimum 1
            return null;
        }

        $iv     = substr($brut, 0, 12);
        $tag    = substr($brut, 12, 16);
        $cifrat = substr($brut, 28);

        $text = openssl_decrypt($cifrat, self::CIFRU, self::cheie(), OPENSSL_RAW_DATA, $iv, $tag);
        return $text === false ? null : $text;
    }

    // -----------------------------------------------------------------------
    // Operatii
    // -----------------------------------------------------------------------

    public static function salveaza(string $nume, string $contact, string $mesaj, ?int $psihologPreferatId = null): int
    {
        return Database::insert(
            'INSERT INTO mesaje_contact (nume_cif, contact_cif, psiholog_preferat_id, mesaj_cif) VALUES (?, ?, ?, ?)',
            [
                self::cripteaza($nume),
                self::cripteaza($contact),
                $psihologPreferatId,
                $mesaj === '' ? null : self::cripteaza($mesaj),
            ]
        );
    }

    /** Mesajele nesterse, decriptate pentru afisare in admin. */
    public static function toate(): array
    {
        // Aducem si numele psihologului preferat (preferinta NU e criptata:
        // nu spune nimic despre starea persoanei, doar cu cine ar vrea sa discute).
        $randuri = Database::all(
            'SELECT m.*, p.nume AS psiholog_preferat
             FROM mesaje_contact m
             LEFT JOIN psihologi p ON p.id = m.psiholog_preferat_id
             WHERE m.sters_la IS NULL ORDER BY m.primit_la DESC'
        );

        foreach ($randuri as &$r) {
            $r['nume']    = self::decripteaza($r['nume_cif'])    ?? '(nu s-a putut decripta)';
            $r['contact'] = self::decripteaza($r['contact_cif']) ?? '(nu s-a putut decripta)';
            $r['mesaj']   = self::decripteaza($r['mesaj_cif']);
            unset($r['nume_cif'], $r['contact_cif'], $r['mesaj_cif']);
        }
        return $randuri;
    }

    /**
     * Stergere reversibila. Randul ramane 30 de zile, ca o apasare gresita sa
     * poata fi anulata, apoi curatarea il elimina definitiv.
     */
    public static function sterge(int $id): void
    {
        Database::run('UPDATE mesaje_contact SET sters_la = NOW() WHERE id = ?', [$id]);
    }

    public static function anuleazaStergerea(int $id): void
    {
        Database::run('UPDATE mesaje_contact SET sters_la = NULL WHERE id = ?', [$id]);
    }

    public static function marcheazaCitit(int $id): void
    {
        Database::run('UPDATE mesaje_contact SET citit = 1 WHERE id = ?', [$id]);
    }

    public static function nrNecitite(): int
    {
        return (int) Database::value(
            'SELECT COUNT(*) FROM mesaje_contact WHERE citit = 0 AND sters_la IS NULL'
        );
    }

    /**
     * Politica de retentie.
     *
     * Sterge definitiv: mesajele marcate ca sterse acum mai bine de 30 de zile,
     * si mesajele mai vechi decat perioada din config. Politica de
     * confidentialitate afiseaza aceeasi valoare — daca o schimbi in config,
     * se schimba si acolo.
     *
     * Se ruleaza din cron. Vezi INSTALARE.md.
     */
    public static function curata(): int
    {
        $zile = max(30, (int) config('retentie_mesaje_zile'));

        $sterse = Database::affected(
            'DELETE FROM mesaje_contact WHERE sters_la IS NOT NULL AND sters_la < NOW() - INTERVAL 30 DAY'
        );
        $sterse += Database::affected(
            'DELETE FROM mesaje_contact WHERE primit_la < NOW() - INTERVAL ? DAY',
            [$zile]
        );

        return $sterse;
    }
}
