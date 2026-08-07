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
<?php /* Marcam ca JS e activ. Doar atunci ascundem elementele .reveal pana le
         face vizibile scriptul. Fara JS (sau daca scriptul nu se incarca),
         textul ramane vizibil — niciodata o pagina goala. */ ?>
<script>document.documentElement.classList.add('js')</script>

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
<meta property="og:site_name" content="<?= e(setare('cabinet_nume', 'Adam și Babotan')) ?>">
<meta property="og:title" content="<?= e($titlu) ?>">
<meta property="og:description" content="<?= e($descriere) ?>">
<meta property="og:url" content="<?= e(url(ltrim($ruta, '/'))) ?>">
<?php
  /* Imaginea de previzualizare pe retele. Se ia coperta articolului daca exista,
     altfel imaginea generala a cabinetului (o pune clienta — vezi CONTINUT.md).
     Cardul e „summary_large_image" doar cand chiar avem o imagine. */
  $ogImg = !empty($og_imagine) ? url('uploads/' . $og_imagine) : url('assets/images/og.webp');
?>
<meta property="og:image" content="<?= e($ogImg) ?>">
<meta property="og:image:alt" content="<?= e(setare('cabinet_nume', 'Adam și Babotan')) ?> — cabinet de psihologie">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="<?= e($ogImg) ?>">

<link rel="icon" href="<?= e(asset('assets/favicon.svg')) ?>" type="image/svg+xml">

<?php /* Schema.org: Person + MedicalBusiness. Ajuta Google sa arate corect
        cabinetul in rezultate locale. */ ?>
<?php
    // Nu punem in structured data campurile inca necompletate ([COMPLETEZ]).
    $sd_valid = fn($v) => is_string($v) && $v !== '' && !str_contains($v, 'COMPLETEZ');
    $sd_tel = config('site', 'telefon');
    $sd_adr = setare('adresa');
    $ld = $schema ?? array_filter([
        '@context' => 'https://schema.org',
        '@type'    => ['MedicalBusiness', 'LocalBusiness'],
        'name'     => config('site', 'nume'),
        'url'      => url(),
        'email'    => config('site', 'email'),
        'telephone'=> $sd_valid($sd_tel) ? $sd_tel : null,
        'medicalSpecialty' => 'Psychiatric',
        'address'  => $sd_valid($sd_adr) ? [
            '@type' => 'PostalAddress',
            'streetAddress'   => $sd_adr,
            'addressCountry'  => 'RO',
        ] : ['@type' => 'PostalAddress', 'addressCountry' => 'RO'],
        'founder' => [
            '@type'    => 'Person',
            'name'     => config('site', 'nume'),
            'jobTitle' => 'Psiholog clinician',
        ],
    ], fn($v) => $v !== null);
?>
<script type="application/ld+json">
<?= json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
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

<!-- Bară de progres la scroll -->
<div class="progres-scroll" aria-hidden="true"><div class="progres-scroll__bara"></div></div>

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

<?php
  $f_email    = (string) config('site', 'email');
  $f_telefon  = (string) config('site', 'telefon');
  $f_whatsapp = (string) config('site', 'whatsapp');
  $are = fn($v) => $v !== '' && !str_contains($v, 'COMPLETEZ');
?>
<footer class="subsol">
  <div class="invelis">

    <div class="subsol__coloane pe-tot-ecranul">

      <div>
        <h4>Contact</h4>
        <ul class="subsol__contact">
          <?php if ($are($f_email)): ?>
            <li>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg>
              <a href="mailto:<?= e($f_email) ?>"><?= e($f_email) ?></a>
            </li>
          <?php endif ?>
          <?php if ($are($f_whatsapp)): ?>
            <li>
              <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.5 15.2L2 22l4.9-1.5A10 10 0 1 0 12 2Zm5.3 14.1c-.2.6-1.2 1.1-1.7 1.2-.4 0-1 .1-1.6-.1-.4-.1-.9-.3-1.5-.6-2.7-1.2-4.4-3.9-4.6-4.1-.1-.2-1-1.4-1-2.6 0-1.2.6-1.8.9-2 .2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.8 1.9c.1.2.1.3 0 .5l-.4.5c-.2.2-.3.3-.1.6.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.4 2.4 1.5.2.1.4.1.5-.1l.7-.8c.2-.2.3-.2.6-.1l1.8.9c.3.1.4.2.5.3.1.2.1.6-.1 1.1Z"/></svg>
              <a href="https://wa.me/<?= e($f_whatsapp) ?>" target="_blank" rel="noopener">Scrie pe WhatsApp</a>
            </li>
          <?php endif ?>
          <?php if ($are($f_telefon)): ?>
            <li>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M5 4h3l2 5-2 1a12 12 0 0 0 5 5l1-2 5 2v3a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/></svg>
              <a href="tel:<?= e(preg_replace('/\s+/', '', $f_telefon)) ?>"><?= e($f_telefon) ?></a>
            </li>
          <?php endif ?>
          <li class="subsol__program" style="white-space:pre-line"><?= e(setare('program')) ?></li>
        </ul>
      </div>

      <div>
        <h4>Cabinet</h4>
        <?php $f_adresa = setare('adresa'); ?>
        <p class="fara-margine-jos">
          <?= e(setare('cabinet_entitate', 'ADAM ȘI BABOTAN, Societate civilă profesională de psihologie')) ?><br>
          <?= e(setare('cabinet_certificat', '')) ?>
          <?php if ($are($f_adresa)): ?><br><?= e($f_adresa) ?><?php endif ?>
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

    <p class="meta pe-tot-ecranul subsol__jos">
      © <?= date('Y') ?> <?= e(setare('cabinet_entitate', config('site', 'nume'))) ?>.
      Acest site nu este un canal de intervenție în criză — pentru urgențe, sună la <strong>112</strong>.
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

<!-- Buton „înapoi sus" — apare la scroll -->
<button class="sus" type="button" aria-label="Înapoi sus">
  <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M12 19V5M5 12l7-7 7 7"/>
  </svg>
</button>

<!-- CTA fix pe mobil, apare după hero -->
<div class="cta-mobil">
  <a class="buton buton--principal" href="<?= e(url('contact')) ?>">Programează o ședință de cunoaștere</a>
</div>

<?= view('layout/banner-cookies') ?>

<script src="<?= e(asset('assets/js/site.js')) ?>" defer></script>
</body>
</html>
