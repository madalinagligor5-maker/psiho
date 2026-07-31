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

/**
 * Elemente grafice decorative, ca SVG inline (recreate după kitul de ilustrație
 * al clientei: valuri, puncte, frunze, blob-uri, cadre). Toate colorate din
 * tokenuri, scalabile, self-hostate. `$clasa` adaugă clase pentru poziționare.
 *
 * Sunt pur decorative — mereu aria-hidden.
 */
function grafic(string $nume, string $clasa = ''): string
{
    $c = $clasa !== '' ? ' ' . $clasa : '';
    $svg = match ($nume) {

        // Linie ondulată — separator blând între secțiuni.
        'val' => '<svg class="g-val'.$c.'" viewBox="0 0 240 16" fill="none" aria-hidden="true">'
            .'<path d="M2 8 Q 20 1 40 8 T 80 8 T 120 8 T 160 8 T 200 8 T 238 8" '
            .'stroke="var(--salvie)" stroke-width="2" stroke-linecap="round"/></svg>',

        // Grup de puncte răsfirate — textură organică.
        'puncte' => '<svg class="g-puncte'.$c.'" viewBox="0 0 90 64" aria-hidden="true" fill="var(--salvie)">'
            .'<circle cx="8" cy="14" r="3"/><circle cx="26" cy="8" r="2.4"/><circle cx="20" cy="30" r="2"/>'
            .'<circle cx="42" cy="20" r="2.8"/><circle cx="58" cy="10" r="2"/><circle cx="52" cy="34" r="2.4"/>'
            .'<circle cx="74" cy="24" r="2.8"/><circle cx="82" cy="44" r="2"/><circle cx="36" cy="48" r="2.4"/>'
            .'<circle cx="64" cy="50" r="2"/></svg>',

        // Rămurică simplă cu frunze — element de natură.
        'frunza' => '<svg class="g-frunza'.$c.'" viewBox="0 0 70 130" fill="none" aria-hidden="true">'
            .'<path d="M35 128 C 31 96 39 72 35 40 C 33 22 35 12 35 4" stroke="var(--salvie-inchis)" stroke-width="1.4" stroke-linecap="round"/>'
            .'<path d="M35 96 C 46 92 58 96 64 106 C 52 108 41 104 35 96 Z" fill="var(--salvie)"/>'
            .'<path d="M35 68 C 24 64 12 68 6 78 C 18 80 29 76 35 68 Z" fill="var(--salvie)" opacity="0.85"/>'
            .'<path d="M35 42 C 46 38 57 42 62 51 C 51 53 41 49 35 42 Z" fill="var(--teracota)" opacity="0.7"/>'
            .'<circle cx="35" cy="10" r="3" fill="var(--teracota)"/></svg>',

        // Blob organic moale — pată de fundal.
        'blob' => '<svg class="g-blob'.$c.'" viewBox="0 0 220 200" aria-hidden="true">'
            .'<path fill="var(--salvie)" opacity="0.22" d="M120 14 C 168 8 210 44 210 96 '
            .'C 210 148 176 190 122 186 C 74 182 24 168 14 116 C 5 68 40 28 74 18 C 92 12 104 16 120 14 Z"/></svg>',

        // Cadru de citat — bulă rotunjită pentru un pull-quote.
        'ghilimea' => '<svg class="g-ghilimea'.$c.'" viewBox="0 0 40 32" aria-hidden="true" fill="var(--salvie)">'
            .'<path d="M4 30 C 2 20 4 8 16 4 L 18 10 C 12 12 10 16 10 20 L 18 20 L 18 30 Z"/>'
            .'<path d="M24 30 C 22 20 24 8 36 4 L 38 10 C 32 12 30 16 30 20 L 38 20 L 38 30 Z"/></svg>',

        default => '',
    };
    return $svg;
}

/**
 * Iconițe line-art (recreate după kitul de ilustrație). viewBox 24×24, contur
 * din `currentColor` (colorezi prin `color` pe container), rotunjite. Decorative
 * — mereu aria-hidden; sensul stă în textul de lângă.
 */
function iconita(string $nume, string $clasa = ''): string
{
    $cai = match ($nume) {
        // Emoții & stări
        'anxietate'  => '<path d="M15 7c-4-2-8 1-7 5 1 3 6 4 7 1 1-2-2-4-4-2s-1 5 2 5"/>',
        'stres'      => '<path d="M7 13a3 3 0 0 1 .3-6 4 4 0 0 1 7.5-1A3 3 0 0 1 16 13Z"/><path d="M11 15l-2 3h3l-2 3"/>',
        'ganduri'    => '<path d="M8 13a3.5 3.5 0 0 1 .5-7 4 4 0 0 1 7.3.5A3 3 0 0 1 15 13Z"/><circle cx="7" cy="17" r="1"/><circle cx="9.5" cy="19.5" r=".7"/>',
        'coplesire'  => '<path d="M4 12h6M4 8h9M4 16h7"/><path d="M17 6c-2 1-2 4 0 5s3-3 1-4-3 3-1 5"/>',
        'respiro'    => '<path d="M12 4v8"/><path d="M12 12c0-3-1-5-4-5-2 0-3 2-3 5s2 6 4 6c2 0 3-3 3-6Z"/><path d="M12 12c0-3 1-5 4-5 2 0 3 2 3 5s-2 6-4 6c-2 0-3-3-3-6Z"/>',
        'liniste'    => '<circle cx="12" cy="12" r="8"/><path d="M9 14c1 1.5 5 1.5 6 0"/><path d="M8.5 10h.01M15.5 10h.01"/>',
        // Concepte & valori
        'echilibru'  => '<path d="M12 4v16M6 20h12"/><path d="M12 6 5 9m7-3 7 3"/><path d="M3 9a2 2 0 0 0 4 0Zm14 0a2 2 0 0 0 4 0Z"/>',
        'sprijin'    => '<path d="M12 20c-1-3-6-4-6-8a2.5 2.5 0 0 1 5-.5A2.5 2.5 0 0 1 16 12"/><path d="M4 15c1 2 3 3 5 3M20 15c-1 2-3 3-5 3"/>',
        'progres'    => '<path d="M4 18h4v-4h4V9h4V5h4"/><path d="M16 5h4v4"/>',
        'conexiune'  => '<circle cx="7" cy="8" r="2.4"/><circle cx="17" cy="8" r="2.4"/><circle cx="12" cy="17" r="2.4"/><path d="M9 9.5 10.5 15m4.5-5.5L13.5 15M9 8h6"/>',
        'comunicare' => '<path d="M4 6h9a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H8l-4 3Z"/><path d="M17 9h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2v3l-3-3"/>',
        'directie'   => '<path d="M12 3v18"/><path d="M12 5h6l2 2-2 2h-6"/><path d="M12 11H6l-2 2 2 2h6"/>',
        'constientizare' => '<path d="M12 3a6 6 0 0 0-3 11v2h6v-2a6 6 0 0 0-3-11Z"/><path d="M10 20h4M11 22h2"/>',
        default => '',
    };
    if ($cai === '') {
        return '';
    }
    $c = $clasa !== '' ? ' ' . $clasa : '';
    return '<svg class="iconita'.$c.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" '
        .'stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
        .$cai.'</svg>';
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
