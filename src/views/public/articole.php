<?php
/** Index articole. Variabile: $articole, $categorii, $pagina, $total, $categorie, $baza_url */
?>

<section class="sectiune">
  <p class="eticheta">Articole</p>
  <h1><?= $categorie ? e($categorie['nume']) : 'Articole' ?></h1>
  <p class="introducere" style="margin-top: var(--s3)">
    <?php if ($categorie && !empty($categorie['descriere'])): ?>
      <?= e($categorie['descriere']) ?>
    <?php else: ?>
      Texte despre anxietate, depresie, burnout și tiparele care se repetă. Scrise
      ca să fie de folos, nu ca să umple pagina.
    <?php endif ?>
  </p>
</section>

<section class="sectiune">
  <!-- Filtre de categorie -->
  <nav class="categorii" aria-label="Filtrează după categorie">
    <a href="<?= e(url('articole')) ?>" <?= $categorie === null ? 'aria-current="page"' : '' ?>>Toate</a>
    <?php foreach ($categorii as $c): ?>
      <a href="<?= e(url('articole/categorie/' . $c['slug'])) ?>"
         <?= $categorie && $categorie['slug'] === $c['slug'] ? 'aria-current="page"' : '' ?>>
        <?= e($c['nume']) ?>
      </a>
    <?php endforeach ?>
  </nav>

  <?php if (empty($articole)): ?>
    <p>Încă nu e niciun articol aici. Revino curând.</p>
  <?php else: ?>
    <div class="articole">
      <?php foreach ($articole as $a): ?>
        <article class="articol-card">
          <p class="meta">
            <?php if (!empty($a['categorie_nume'])): ?><?= e($a['categorie_nume']) ?> · <?php endif ?>
            <?= reading_time($a['continut']) ?> min de citit
            <?php if (!empty($a['publicat_la'])): ?> · <?= e(data_ro($a['publicat_la'])) ?><?php endif ?>
          </p>
          <h3><a href="<?= e(url('articol/' . $a['slug'])) ?>"><?= e($a['titlu']) ?></a></h3>
          <p><?= e($a['rezumat']) ?></p>
          <p class="fara-margine-jos"><a href="<?= e(url('articol/' . $a['slug'])) ?>">Citește →</a></p>
        </article>
      <?php endforeach ?>
    </div>

    <!-- Paginare. Doar pe lista completa: filtrarea pe categorie afiseaza o
         singura pagina, ca sa nu inmultim rutele fara motiv real la 3 articole. -->
    <?php if ($total > 1 && $categorie === null): ?>
      <nav class="paginare" aria-label="Paginare articole">
        <?php if ($pagina > 1): ?>
          <a href="<?= e(url(ltrim($baza_url, '/') . ($pagina - 1 > 1 ? '/pagina/' . ($pagina - 1) : ''))) ?>">← Anterioare</a>
        <?php endif ?>
        <?php for ($i = 1; $i <= $total; $i++): ?>
          <?php if ($i === $pagina): ?>
            <span aria-current="page"><?= $i ?></span>
          <?php else: ?>
            <a href="<?= e(url(ltrim($baza_url, '/') . ($i > 1 ? '/pagina/' . $i : ''))) ?>"><?= $i ?></a>
          <?php endif ?>
        <?php endfor ?>
        <?php if ($pagina < $total): ?>
          <a href="<?= e(url(ltrim($baza_url, '/') . '/pagina/' . ($pagina + 1))) ?>">Următoare →</a>
        <?php endif ?>
      </nav>
    <?php endif ?>
  <?php endif ?>
</section>

<?= view('public/_cta') ?>
