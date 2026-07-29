<?php
/**
 * Layoutul comun al tuturor paginilor publice.
 *
 * Variabile asteptate:
 *   $continut        HTML-ul paginii (pus de render())
 *   $titlu           titlul din <title>
 *   $descriere       meta description
 *   $ruta            calea curenta, pentru aria-current in navigatie
 *   $schema          array optional cu JSON-LD suplimentar
 *   $fara_fir        true ca sa nu se deseneze firul (pagini scurte)
 */

$titlu     = $titlu     ?? config('site', 'titlu');
$descriere = $descriere ?? '';
$ruta      = $ruta      ?? '/';
$schema    = $schema    ?? null;
$fara_fir  = $fara_fir  ?? false;

$navigatie = [
    '/cum-functioneaza' => 'Cum funcționează',
    '/servicii'         => 'Servicii',
    '/echipa'           => 'Echipa',
    '/articole'         => 'Articole',
    '/resurse'          => 'Resurse',
    '/contact'          => 'Contact',
];

$whatsapp = (string) config('site', 'whatsapp');
$analiza  = config('analiza');
?>
<!doctype html>
<html lang="ro">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= e($titlu) ?></title>
<?php if ($descriere !== ''): ?>
<meta name="description" content="<?= e($descriere) ?>">
<?php endif ?>
<link rel="canonical" href="<?= e(url(ltrim($ruta, '/'))) ?>">

<?php /* Fonturile sunt pe acelasi domeniu. Preincarcam doar subsetul latin al
        fontului de corp: e primul lucru de care are nevoie randarea textului,
        iar latin-ext se cere oricum imediat dupa, pentru diacritice. */ ?>
<link rel="preload" href="<?= e(asset('assets/fonts/AtkinsonNext-normal-latin.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= e(asset('assets/css/site.css')) ?>">

<meta property="og:type" content="website">
<meta property="og:locale" content="ro_RO">
<meta property="og:title" content="<?= e($titlu) ?>">
<meta property="og:description" content="<?= e($descriere) ?>">
<meta property="og:url" content="<?= e(url(ltrim($ruta, '/'))) ?>">
<meta name="twitter:card" content="summary">

<link rel="icon" href="<?= e(asset('assets/favicon.svg')) ?>" type="image/svg+xml">

<?php /* Schema.org: Person + MedicalBusiness. Ajuta Google sa arate corect
        cabinetul in rezultate locale. */ ?>
<script type="application/ld+json">
<?= json_encode($schema ?? [
    '@context' => 'https://schema.org',
    '@type'    => ['MedicalBusiness', 'LocalBusiness'],
    'name'     => config('site', 'nume'),
    'url'      => url(),
    'email'    => config('site', 'email'),
    'telephone'=> config('site', 'telefon'),
    'medicalSpecialty' => 'Psychiatric',
    'address'  => [
        '@type' => 'PostalAddress',
        'streetAddress'   => setare('adresa'),
        'addressCountry'  => 'RO',
    ],
    'founder' => [
        '@type'    => 'Person',
        'name'     => config('site', 'nume'),
        'jobTitle' => 'Psiholog clinician',
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>

<?php /* Analiza se incarca DUPA consimtamant, din site.js. Scriptul nu e pus
        aici: pana la accept, niciun request catre terti. */ ?>
<?php if (!empty($analiza['domeniu'])): ?>
<meta name="analiza-script"  content="<?= e($analiza['script']) ?>">
<meta name="analiza-domeniu" content="<?= e($analiza['domeniu']) ?>">
<?php endif ?>
</head>

<body>

<a class="sari-la-continut" href="#continut">Sari la conținut</a>

<header class="antet">
  <div class="invelis">
    <div class="antet__interior pe-tot-ecranul">

      <a class="marca" href="<?= e(url()) ?>">
        <?= e(setare('cabinet_nume', 'Adam și Babotan')) ?>
        <span>Cabinet de psihologie · Psihoterapie sistemică</span>
      </a>

      <nav class="navigatie" aria-label="Navigare principală">
        <?php foreach ($navigatie as $cale => $eticheta): ?>
          <a href="<?= e(url(ltrim($cale, '/'))) ?>"
             <?= $ruta === $cale ? 'aria-current="page"' : '' ?>><?= e($eticheta) ?></a>
        <?php endforeach ?>
      </nav>

      <button class="buton-meniu" type="button"
              aria-expanded="false" aria-controls="meniu-mobil">
        Meniu
      </button>

    </div>

    <nav class="meniu-mobil pe-tot-ecranul" id="meniu-mobil"
         aria-label="Navigare principală" data-deschis="false">
      <?php foreach ($navigatie as $cale => $eticheta): ?>
        <a href="<?= e(url(ltrim($cale, '/'))) ?>"
           <?= $ruta === $cale ? 'aria-current="page"' : '' ?>><?= e($eticheta) ?></a>
      <?php endforeach ?>
    </nav>
  </div>
</header>

<main class="invelis <?= $fara_fir ? '' : 'cu-fir' ?>" id="continut">
<?= $continut ?>
</main>

<footer class="subsol">
  <div class="invelis">
    <div class="subsol__coloane pe-tot-ecranul">

      <div>
        <h4>Cabinet</h4>
        <p class="fara-margine-jos">
          <?= e(setare('cabinet_entitate', 'ADAM ȘI BABOTAN, Societate civilă profesională de psihologie')) ?><br>
          <?= e(setare('cabinet_certificat', '')) ?><br>
          <?= e(setare('adresa')) ?>
        </p>
      </div>

      <div>
        <h4>Pagini</h4>
        <ul>
          <?php foreach ($navigatie as $cale => $eticheta): ?>
            <li><a href="<?= e(url(ltrim($cale, '/'))) ?>"><?= e($eticheta) ?></a></li>
          <?php endforeach ?>
        </ul>
      </div>

      <div>
        <h4>Legal</h4>
        <ul>
          <li><a href="<?= e(url('politica-de-confidentialitate')) ?>">Politica de confidențialitate</a></li>
          <li><a href="<?= e(url('politica-de-cookies')) ?>">Politica de cookies</a></li>
          <li><a href="<?= e(url('termeni-si-conditii')) ?>">Termeni și condiții</a></li>
        </ul>
      </div>

    </div>

    <p class="meta pe-tot-ecranul" style="margin-top: var(--s4)">
      © <?= date('Y') ?> <?= e(setare('cabinet_entitate', config('site', 'nume'))) ?>.
      Acest site nu este un canal de intervenție în criză.
    </p>
  </div>
</footer>

<?php if ($whatsapp !== '' && !str_contains($whatsapp, 'COMPLETEZ')): ?>
<a class="whatsapp"
   href="https://wa.me/<?= e($whatsapp) ?>"
   target="_blank" rel="noopener"
   aria-label="Scrie pe WhatsApp">
  <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
    <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2Zm0 18.13h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.36c0-4.54 3.7-8.24 8.25-8.24 2.2 0 4.27.86 5.83 2.42a8.19 8.19 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24Zm4.52-6.17c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.16.24-.64.8-.78.97-.14.16-.29.18-.54.06-.25-.13-1.05-.39-2-1.23-.74-.66-1.24-1.47-1.38-1.72-.15-.25-.02-.38.11-.5.11-.11.25-.29.37-.43.13-.15.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.35-.77-1.84-.2-.49-.4-.42-.55-.43h-.47c-.16 0-.43.06-.65.31-.22.25-.85.83-.85 2.03s.87 2.35.99 2.51c.12.17 1.71 2.61 4.14 3.66.58.25 1.03.4 1.38.51.58.19 1.11.16 1.53.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.05.14-1.16-.06-.1-.22-.16-.47-.28Z"/>
  </svg>
</a>
<?php endif ?>

<?= view('layout/banner-cookies') ?>

<script src="<?= e(asset('assets/js/site.js')) ?>" defer></script>
</body>
</html>
