<?php
/**
 * Layoutul panoului de admin: bara laterala + continut.
 * Variabile: $continut, $titlu, $nr_mesaje, $utilizator
 */
$titlu = $titlu ?? 'Administrare';
$rutaCurenta = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$nrMesaje = $nr_mesaje ?? 0;

$meniu = [
    '/admin'          => ['Tablou de bord', false],
    '/admin/articole' => ['Articole', false],
    '/admin/mesaje'   => ['Mesaje', true],
    '/admin/faq'      => ['Întrebări frecvente', false],
    '/admin/resurse'  => ['Resurse', false],
    '/admin/setari'   => ['Setări', false],
];

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!doctype html>
<html lang="ro">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($titlu) ?> — Administrare</title>
<link rel="preload" href="<?= e(asset('assets/fonts/AtkinsonNext-normal-latin.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= e(asset('assets/css/site.css')) ?>">
<link rel="stylesheet" href="<?= e(asset('assets/css/admin.css')) ?>">
</head>
<body class="admin">

<div class="admin-cadru">

  <aside class="admin-bara">
    <a class="admin-marca" href="<?= e(url('admin')) ?>">
      Administrare
      <span><?= e(config('site', 'nume')) ?></span>
    </a>

    <nav class="admin-nav" aria-label="Meniu administrare">
      <?php foreach ($meniu as $cale => [$eticheta, $areInsigna]): ?>
        <?php
          // Potrivire: exact, sau prefix pentru sub-pagini (ex. /admin/articole/nou).
          $activ = $rutaCurenta === $cale
              || ($cale !== '/admin' && str_starts_with($rutaCurenta, $cale));
        ?>
        <a href="<?= e(url(ltrim($cale, '/'))) ?>" <?= $activ ? 'aria-current="page"' : '' ?>>
          <span><?= e($eticheta) ?></span>
          <?php if ($areInsigna && $nrMesaje > 0): ?>
            <span class="admin-insigna" aria-label="<?= $nrMesaje ?> mesaje noi"><?= $nrMesaje ?></span>
          <?php endif ?>
        </a>
      <?php endforeach ?>
    </nav>

    <div class="admin-jos">
      <p style="margin:0 0 var(--s1)"><?= e($utilizator['nume'] ?? '') ?></p>
      <a href="<?= e(url()) ?>" target="_blank" rel="noopener" style="color:var(--on-ink)">Vezi site-ul ↗</a>
      <form method="post" action="<?= e(url('admin/iesire')) ?>" style="margin-top:var(--s1)">
        <?= Auth::campCsrf() ?>
        <button type="submit" class="admin-iesire">Ieși din cont</button>
      </form>
    </div>
  </aside>

  <main class="admin-continut">
    <?php if ($flash): ?>
      <div class="flash <?= $flash['tip'] === 'eroare' ? 'flash--eroare' : '' ?>" role="status">
        <span class="flash__semn" aria-hidden="true"><?= $flash['tip'] === 'eroare' ? '!' : '✓' ?></span>
        <span><?= e($flash['mesaj']) ?></span>
      </div>
    <?php endif ?>

    <?= $continut ?>
  </main>

</div>

<script src="<?= e(asset('assets/js/admin.js')) ?>" defer></script>
</body>
</html>
