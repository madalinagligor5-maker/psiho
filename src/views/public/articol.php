<?php
/** Un articol. Variabile: $articol, $inrudite */
?>

<article>
  <section class="sectiune">
    <p class="eticheta">
      <?php if (!empty($articol['categorie_nume'])): ?>
        <a href="<?= e(url('articole/categorie/' . $articol['categorie_slug'])) ?>"><?= e($articol['categorie_nume']) ?></a>
      <?php else: ?>
        Articol
      <?php endif ?>
    </p>
    <h1><?= e($articol['titlu']) ?></h1>
    <p class="meta" style="margin-top: var(--s2)">
      <?= reading_time($articol['continut']) ?> min de citit
      <?php if (!empty($articol['publicat_la'])): ?> · <?= e(data_ro($articol['publicat_la'])) ?><?php endif ?>
    </p>
  </section>

  <?php if (!empty($articol['imagine'])): ?>
    <section class="sectiune" style="padding-block-start: 0">
      <img class="pe-tot-ecranul" src="<?= e(url('uploads/' . $articol['imagine'])) ?>"
           alt="<?= e($articol['imagine_alt'] ?? '') ?>"
           width="1200" height="675" style="border-radius: var(--raza-mediu)">
    </section>
  <?php endif ?>

  <section class="sectiune" style="padding-block-start: 0">
    <div class="proza">
      <?= Markdown::toHtml($articol['continut']) ?>
    </div>
  </section>
</article>

<?php if (!empty($inrudite)): ?>
<section class="sectiune">
  <p class="eticheta">De citit mai departe</p>
  <div class="articole" style="margin-top: var(--s3)">
    <?php foreach ($inrudite as $a): ?>
      <article class="articol-card">
        <p class="meta"><?= reading_time($a['continut']) ?> min de citit</p>
        <h3><a href="<?= e(url('articol/' . $a['slug'])) ?>"><?= e($a['titlu']) ?></a></h3>
        <p class="fara-margine-jos"><?= e($a['rezumat']) ?></p>
      </article>
    <?php endforeach ?>
  </div>
</section>
<?php endif ?>

<?= view('public/_cta') ?>
