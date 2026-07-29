<?php
declare(strict_types=1);

/**
 * Controlerele paginilor publice.
 *
 * Fiecare functie e un handler de ruta. Copy-ul real sta in sabloanele din
 * src/views/public/. Textele scurte, care se schimba des (preturi, adresa),
 * vin din setari; textele lungi, care sunt parte din design, stau in sabloane.
 */

// ---------------------------------------------------------------------------
// Acasa
// ---------------------------------------------------------------------------

function pagina_acasa(): void
{
    render('public/acasa', [
        'titlu'     => setare('cabinet_nume', 'Adam și Babotan') . ' — cabinet de psihologie, psihoterapie sistemică',
        'descriere' => 'Cabinet de psihologie din Timișoara. Anxietate, depresie, burnout, terapie de familie, '
            . 'evaluări și documentație psihologică. „Nu caut vinovatul, caut tiparul.”',
        'ruta'      => '/',
        'articole'  => Articol::recente(3),
        'faq'       => Faq::toate(true),
        'psihologi' => Psiholog::toti(),
    ]);
}

// ---------------------------------------------------------------------------
// Cum functioneaza — pagina cea mai importanta (§5)
// ---------------------------------------------------------------------------

function pagina_cum_functioneaza(): void
{
    render('public/cum-functioneaza', [
        'titlu'     => 'Cum funcționează — ' . setare('cabinet_nume', 'Adam și Babotan'),
        'descriere' => 'Ce se întâmplă în prima ședință, minut cu minut. Cât durează, cât costă, '
            . 'ce e terapia sistemică, ce se întâmplă cu ce spui acolo.',
        'ruta'      => '/cum-functioneaza',
    ]);
}

// ---------------------------------------------------------------------------
// Servicii
// ---------------------------------------------------------------------------

function pagina_servicii(): void
{
    render('public/servicii', [
        'titlu'     => 'Servicii și prețuri — ' . setare('cabinet_nume', 'Adam și Babotan'),
        'descriere' => 'Ședințe individuale la cabinet și online. Prețuri, durată, cui se potrivește fiecare.',
        'ruta'      => '/servicii',
    ]);
}

// ---------------------------------------------------------------------------
// Despre mine
// ---------------------------------------------------------------------------

function pagina_echipa(): void
{
    // Atașează specializările fiecărui psiholog.
    $psihologi = Psiholog::toti();
    foreach ($psihologi as &$p) {
        $p['specializari'] = Psiholog::specializari((int) $p['id']);
    }
    unset($p);

    render('public/echipa', [
        'titlu'     => 'Echipa — ' . setare('cabinet_nume', 'Adam și Babotan'),
        'descriere' => 'Cele două psiholoage ale cabinetului: formare, acreditare (cod CPR verificabil), '
            . 'specializări și regimul de practică al fiecăreia.',
        'ruta'      => '/echipa',
        'psihologi' => $psihologi,
    ]);
}

/** Redirect de la vechea rută /despre-mine la /echipa. */
function pagina_despre_redirect(): void
{
    redirect('/echipa', 301);
}

// ---------------------------------------------------------------------------
// Articole
// ---------------------------------------------------------------------------

function pagina_articole(string $numar = '1'): void
{
    $pagina = max(1, (int) $numar);
    $total  = Articol::numarPagini();

    render('public/articole', [
        'titlu'      => 'Articole — ' . setare('cabinet_nume', 'Adam și Babotan'),
        'descriere'  => 'Texte despre anxietate, depresie, burnout și tiparele care se repetă.',
        'ruta'       => '/articole',
        'articole'   => Articol::pagina($pagina),
        'categorii'  => Articol::categorii(),
        'pagina'     => $pagina,
        'total'      => $total,
        'categorie'  => null,
        'baza_url'   => '/articole',
    ]);
}

function pagina_categorie(string $slug): void
{
    $categorie = Articol::categorieDupaSlug($slug);
    if ($categorie === null) {
        pagina_negasita();
        return;
    }

    render('public/articole', [
        'titlu'      => $categorie['nume'] . ' — articole — ' . setare('cabinet_nume', 'Adam și Babotan'),
        'descriere'  => $categorie['descriere'] ?? '',
        'ruta'       => '/articole',
        'articole'   => Articol::pagina(1, $slug),
        'categorii'  => Articol::categorii(),
        'pagina'     => 1,
        'total'      => Articol::numarPagini($slug),
        'categorie'  => $categorie,
        'baza_url'   => '/articole/categorie/' . $slug,
    ]);
}

function pagina_articol(string $slug): void
{
    $articol = Articol::dupaSlug($slug);
    if ($articol === null) {
        pagina_negasita();
        return;
    }

    // Schema.org Article, pentru ca articolele sa apara corect in cautare.
    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'Article',
        'headline' => $articol['titlu'],
        'datePublished' => $articol['publicat_la'],
        'dateModified'  => $articol['actualizat_la'],
        // Autorul articolului e psihologa care l-a scris, dacă e atribuit.
        'author'   => ['@type' => 'Person', 'name' => $articol['autor_nume'] ?? setare('cabinet_nume', 'Adam și Babotan')],
        'description' => $articol['meta_descriere'] ?: $articol['rezumat'],
    ];

    render('public/articol', [
        'titlu'     => $articol['titlu'] . ' — ' . setare('cabinet_nume', 'Adam și Babotan'),
        'descriere' => $articol['meta_descriere'] ?: $articol['rezumat'],
        'ruta'      => '/articole',
        'articol'   => $articol,
        'inrudite'  => Articol::inrudite($articol, 2),
        'schema'    => $schema,
    ]);
}

// ---------------------------------------------------------------------------
// Resurse
// ---------------------------------------------------------------------------

function pagina_resurse(): void
{
    render('public/resurse', [
        'titlu'     => 'Resurse — ' . setare('cabinet_nume', 'Adam și Babotan'),
        'descriere' => 'Jurnale și ghiduri de lucru. Se cer prin email.',
        'ruta'      => '/resurse',
        'resurse'   => Resursa::toate(),
    ]);
}

// ---------------------------------------------------------------------------
// Contact
// ---------------------------------------------------------------------------

function pagina_contact(array $date = []): void
{
    render('public/contact', array_merge([
        'titlu'      => 'Contact — ' . setare('cabinet_nume', 'Adam și Babotan'),
        'descriere'  => 'Programează o ședință de cunoaștere. Formular scurt, WhatsApp, email.',
        'ruta'       => '/contact',
        'fara_fir'   => true,
        'vechi'      => [],
        'erori'      => [],
        'trimis'     => false,
        'psihologi'  => Psiholog::pentruSelect(),
    ], $date));
}

/**
 * Procesarea formularului de contact.
 *
 * Protectie anti-spam fara reCAPTCHA:
 *   1. camp-capcana ("website") — completat doar de roboti
 *   2. verificare de timp — formularul completat prea repede e robot
 * Impreuna inlocuiesc reCAPTCHA si evita transferul de date catre Google.
 */
function trimite_contact(): void
{
    Auth::cereCsrf();

    // 1. Capcana. Un om nu vede campul, deci nu-l completeaza.
    if (trim((string) ($_POST['website'] ?? '')) !== '') {
        // Raspundem cu succes ca robotul sa nu invete ca a fost prins.
        pagina_contact(['trimis' => true]);
        return;
    }

    // 2. Timp. Formularul incarcat si trimis in mai putin de 3 secunde e robot.
    $incarcat = (int) ($_POST['incarcat_la'] ?? 0);
    if ($incarcat > 0 && (time() - $incarcat) < 3) {
        pagina_contact(['trimis' => true]);
        return;
    }

    $nume      = trim((string) ($_POST['nume'] ?? ''));
    $contact   = trim((string) ($_POST['contact'] ?? ''));
    $preferat  = (int) ($_POST['preferat'] ?? 0);
    $mesaj     = trim((string) ($_POST['mesaj'] ?? ''));

    $erori = [];
    if (mb_strlen($nume) < 2) {
        $erori['nume'] = 'Spune-ne cum să-ți spunem.';
    }
    if (mb_strlen($contact) < 5) {
        $erori['contact'] = 'Avem nevoie de un email sau un telefon ca să-ți putem răspunde.';
    }

    // Preferința e opțională; dacă e dată, trebuie să fie un psiholog real.
    $preferatId = null;
    if ($preferat > 0) {
        $preferatId = Psiholog::dupaId($preferat) !== null ? $preferat : null;
    }

    if ($erori !== []) {
        pagina_contact([
            'erori' => $erori,
            'vechi' => ['nume' => $nume, 'contact' => $contact, 'preferat' => $preferat, 'mesaj' => $mesaj],
        ]);
        return;
    }

    Mesaj::salveaza($nume, $contact, $mesaj, $preferatId);
    trimite_email_notificare($nume, $contact, $preferatId, $mesaj);

    pagina_contact(['trimis' => true]);
}

/**
 * Trimite notificarea prin SMTP.
 *
 * Implementare SMTP proprie, scurta, in loc de PHPMailer: un singur tip de
 * mesaj, text simplu, fara atasamente. mail() ajunge in spam de pe gazduire
 * partajata, deci nu e o optiune.
 */
function trimite_email_notificare(string $nume, string $contact, ?int $preferatId, string $mesaj): void
{
    $mail = config('mail');
    if (empty($mail['host']) || empty($mail['destinatar']) || str_contains((string) $mail['destinatar'], 'COMPLETEZ')) {
        return; // email neconfigurat inca — mesajul e deja salvat in baza
    }

    $preferat = $preferatId ? (Psiholog::dupaId($preferatId)['nume'] ?? '') : '';
    $corp = "Mesaj nou de pe site.\n\n"
        . "Nume: {$nume}\n"
        . "Contact: {$contact}\n"
        . 'Preferă să discute cu: ' . ($preferat !== '' ? $preferat : 'nu a specificat') . "\n\n"
        . ($mesaj !== '' ? "Mesaj:\n{$mesaj}\n" : "(fără mesaj)\n")
        . "\n---\nVezi în panou: " . url('admin/mesaje');

    try {
        smtp_trimite(
            $mail,
            $mail['destinatar'],
            'Mesaj nou de pe site',
            $corp
        );
    } catch (Throwable $e) {
        // Mesajul e salvat oricum. Notificarea prin email e un plus, nu esenta.
        error_log('Notificare email eșuată: ' . $e->getMessage());
    }
}

// ---------------------------------------------------------------------------
// Pagini legale
// ---------------------------------------------------------------------------

function pagina_confidentialitate(): void
{
    render('public/legal-confidentialitate', [
        'titlu'     => 'Politica de confidențialitate — ' . setare('cabinet_nume', 'Adam și Babotan'),
        'descriere' => 'Cum sunt tratate datele tale, inclusiv datele de sănătate (categorie specială, Art. 9 GDPR).',
        'ruta'      => '/politica-de-confidentialitate',
        'fara_fir'  => true,
    ]);
}

function pagina_cookies(): void
{
    render('public/legal-cookies', [
        'titlu'     => 'Politica de cookies — ' . setare('cabinet_nume', 'Adam și Babotan'),
        'descriere' => 'Ce se stochează în browserul tău și de ce.',
        'ruta'      => '/politica-de-cookies',
        'fara_fir'  => true,
    ]);
}

function pagina_termeni(): void
{
    render('public/legal-termeni', [
        'titlu'     => 'Termeni și condiții — ' . setare('cabinet_nume', 'Adam și Babotan'),
        'descriere' => 'Condițiile de folosire a acestui site.',
        'ruta'      => '/termeni-si-conditii',
        'fara_fir'  => true,
    ]);
}

// ---------------------------------------------------------------------------
// 404
// ---------------------------------------------------------------------------

function pagina_negasita(): void
{
    http_response_code(404);
    render('public/negasit', [
        'titlu'    => 'Pagină negăsită — ' . setare('cabinet_nume', 'Adam și Babotan'),
        'ruta'     => '',
        'fara_fir' => true,
    ]);
}

// ---------------------------------------------------------------------------
// Fisiere generate: sitemap si robots
// ---------------------------------------------------------------------------

function genereaza_sitemap(): void
{
    header('Content-Type: application/xml; charset=utf-8');

    $urls = [
        ['/', '1.0'], ['/cum-functioneaza', '0.9'], ['/servicii', '0.8'],
        ['/despre-mine', '0.7'], ['/articole', '0.7'], ['/resurse', '0.6'],
        ['/contact', '0.8'],
    ];

    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    foreach ($urls as [$cale, $prioritate]) {
        echo "  <url><loc>" . e(url(ltrim($cale, '/'))) . "</loc>"
            . "<priority>{$prioritate}</priority></url>\n";
    }

    foreach (Articol::pagina(1) as $a) {
        // Lista completa ar cere paginare; pentru un sitemap simplu, prima
        // pagina de articole plus fiecare articol acopera ce conteaza.
    }
    foreach (Database::all("SELECT slug, actualizat_la FROM articole WHERE stare = 'publicat' AND publicat_la <= NOW()") as $a) {
        echo "  <url><loc>" . e(url('articol/' . $a['slug'])) . "</loc>"
            . "<lastmod>" . date('Y-m-d', strtotime($a['actualizat_la'])) . "</lastmod>"
            . "<priority>0.6</priority></url>\n";
    }

    echo '</urlset>';
}

function genereaza_robots(): void
{
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-agent: *\n";
    echo "Disallow: /admin\n";
    echo "Disallow: /admin/\n\n";
    echo "Sitemap: " . url('sitemap.xml') . "\n";
}
