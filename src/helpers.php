<?php
declare(strict_types=1);

/**
 * Functii folosite peste tot. Incarcate primele, inainte de orice altceva.
 */

/**
 * Citeste o cheie din config.php.
 * config('site') da tot blocul, config('site', 'nume') da o singura valoare.
 */
function config(string $sectiune, ?string $cheie = null): mixed
{
    static $config = null;
    if ($config === null) {
        $cale = dirname(__DIR__) . '/config/config.php';
        if (!is_file($cale)) {
            http_response_code(500);
            exit('Lipseste config/config.php. Copiaza config/config.example.php si completeaza-l.');
        }
        $config = require $cale;
    }

    $valoare = $config[$sectiune] ?? null;
    return $cheie === null ? $valoare : ($valoare[$cheie] ?? null);
}

/**
 * Escapare pentru HTML. Prescurtarea se foloseste in TOATE sabloanele.
 * Regula proiectului: nicio variabila nu ajunge in HTML fara sa treaca prin e().
 */
function e(?string $text): string
{
    return htmlspecialchars($text ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** URL absolut catre o cale din site. */
function url(string $cale = ''): string
{
    return rtrim((string) config('site', 'url'), '/') . '/' . ltrim($cale, '/');
}

/**
 * URL catre un fisier din assets, cu parametru de versiune bazat pe data
 * modificarii. Asta permite cache pe un an in .htaccess fara ca vizitatorii
 * sa ramana cu CSS vechi dupa o actualizare.
 */
function asset(string $cale): string
{
    $cale = ltrim($cale, '/');
    $fisier = dirname(__DIR__) . '/public_html/' . $cale;
    $versiune = is_file($fisier) ? filemtime($fisier) : 0;
    return url($cale) . '?v=' . $versiune;
}

/**
 * Transforma un titlu in slug, cu diacriticele romanesti transliterate corect.
 *
 * Tratam explicit si formele cu sedila (ş U+015F, ţ U+0163), pentru ca textul
 * lipit din Word contine adesea varianta gresita in loc de cea cu virgula.
 */
function slugify(string $text): string
{
    $harta = [
        'ă' => 'a', 'â' => 'a', 'î' => 'i', 'ș' => 's', 'ț' => 't',
        'Ă' => 'a', 'Â' => 'a', 'Î' => 'i', 'Ș' => 's', 'Ț' => 't',
        // variantele cu sedila, mostenite din codificari vechi
        'ş' => 's', 'ţ' => 't', 'Ş' => 's', 'Ţ' => 't',
    ];

    $text = strtr($text, $harta);
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[^a-z0-9]+/u', '-', $text) ?? '';

    return trim($text, '-');
}

/** Timp estimat de citire, in minute. Minimum 1. */
function reading_time(string $text): int
{
    // ~200 de cuvinte pe minut, media pentru citit pe ecran in romana.
    $cuvinte = str_word_count(strip_tags($text), 0, 'aăâbcdefghiîjklmnopqrsștțuvwxyz');
    return max(1, (int) ceil($cuvinte / 200));
}

/** Data in format romanesc: 14 martie 2026. */
function data_ro(?string $data): string
{
    if (!$data) {
        return '';
    }
    $luni = [
        1 => 'ianuarie', 'februarie', 'martie', 'aprilie', 'mai', 'iunie',
        'iulie', 'august', 'septembrie', 'octombrie', 'noiembrie', 'decembrie',
    ];
    $ts = strtotime($data);
    return date('j', $ts) . ' ' . $luni[(int) date('n', $ts)] . ' ' . date('Y', $ts);
}

/** Randeaza un sablon din src/views cu variabilele date. */
function view(string $sablon, array $date = []): string
{
    $cale = dirname(__DIR__) . '/src/views/' . $sablon . '.php';
    if (!is_file($cale)) {
        throw new RuntimeException("Sablon inexistent: {$sablon}");
    }

    extract($date, EXTR_SKIP);
    ob_start();
    require $cale;
    return (string) ob_get_clean();
}

/** Randeaza o pagina completa: continutul, pus in layout. */
function render(string $sablon, array $date = []): void
{
    $date['continut'] = view($sablon, $date);
    echo view('layout/pagina', $date);
}

/** Redirect si oprire. */
function redirect(string $cale, int $cod = 302): never
{
    header('Location: ' . (str_starts_with($cale, 'http') ? $cale : url($cale)), true, $cod);
    exit;
}

/** Setarile din tabela `setari`, citite o singura data pe request. */
function setare(string $cheie, string $implicit = ''): string
{
    static $setari = null;
    if ($setari === null) {
        $setari = [];
        foreach (Database::all('SELECT cheie, valoare FROM setari') as $rand) {
            $setari[$rand['cheie']] = $rand['valoare'];
        }
    }
    return $setari[$cheie] ?? $implicit;
}

/**
 * Trimite un email prin SMTP, direct pe socket.
 *
 * Scris de mana in loc de PHPMailer: site-ul trimite un singur fel de mesaj —
 * text simplu, un destinatar, fara atasamente. O dependinta intreaga pentru
 * atat nu se justifica. mail() nu e o optiune: pe gazduire partajata ajunge
 * aproape sigur in spam.
 *
 * Arunca RuntimeException daca serverul raspunde cu un cod neasteptat.
 */
function smtp_trimite(array $mail, string $catre, string $subiect, string $corp): void
{
    $securizat = $mail['securizat'] ?? 'ssl';
    $gazda = ($securizat === 'ssl' ? 'ssl://' : '') . $mail['host'];

    $fp = @fsockopen($gazda, (int) $mail['port'], $errno, $errstr, 15);
    if (!$fp) {
        throw new RuntimeException("Conectare SMTP eșuată: {$errstr} ({$errno})");
    }
    stream_set_timeout($fp, 15);

    // Citeste raspunsul si verifica primul cod de trei cifre.
    $citeste = function () use ($fp): string {
        $raspuns = '';
        while (($linie = fgets($fp, 512)) !== false) {
            $raspuns .= $linie;
            // Ultima linie a unui raspuns multi-linie are spatiu dupa cod.
            if (isset($linie[3]) && $linie[3] === ' ') {
                break;
            }
        }
        return $raspuns;
    };
    $comanda = function (string $cmd, array $coduriOk) use ($fp, $citeste): void {
        if ($cmd !== '') {
            fputs($fp, $cmd . "\r\n");
        }
        $raspuns = $citeste();
        $cod = (int) substr(ltrim($raspuns), 0, 3);
        if (!in_array($cod, $coduriOk, true)) {
            throw new RuntimeException("SMTP a răspuns {$cod}: " . trim($raspuns));
        }
    };

    $domeniu = parse_url((string) config('site', 'url'), PHP_URL_HOST) ?: 'localhost';
    $expeditor = $mail['expeditor'];

    $comanda('', [220]);
    $comanda("EHLO {$domeniu}", [250]);

    if (($securizat) === 'tls') {
        $comanda('STARTTLS', [220]);
        if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            throw new RuntimeException('Negocierea TLS a eșuat.');
        }
        $comanda("EHLO {$domeniu}", [250]);
    }

    $comanda('AUTH LOGIN', [334]);
    $comanda(base64_encode((string) $mail['user']), [334]);
    $comanda(base64_encode((string) $mail['pass']), [235]);

    $comanda("MAIL FROM:<{$expeditor}>", [250]);
    $comanda("RCPT TO:<{$catre}>", [250, 251]);
    $comanda('DATA', [354]);

    // Antetele si corpul. Subiectul e codat MIME ca sa treaca diacriticele.
    $antete =
        "From: =?UTF-8?B?" . base64_encode('Site — ' . config('site', 'nume')) . "?= <{$expeditor}>\r\n"
        . "To: <{$catre}>\r\n"
        . "Subject: =?UTF-8?B?" . base64_encode($subiect) . "?=\r\n"
        . "MIME-Version: 1.0\r\n"
        . "Content-Type: text/plain; charset=UTF-8\r\n"
        . "Content-Transfer-Encoding: 8bit\r\n";

    // Un punct singur pe o linie inseamna sfarsit; il dublam daca apare in corp.
    $corpSigur = preg_replace('/^\./m', '..', $corp);
    $comanda($antete . "\r\n" . $corpSigur . "\r\n.", [250]);
    $comanda('QUIT', [221, 250]);

    fclose($fp);
}
