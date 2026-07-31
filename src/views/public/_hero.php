<?php
/**
 * Hero soft reutilizabil pentru paginile interioare (model: ilustrația clientei).
 * Fundal luminos cu forme organice, plantă botanică, punctulețe.
 *
 * Variabile:
 *   $h_eticheta  — eyebrow (text simplu)
 *   $h_titlu     — titlul H1 (text simplu)
 *   $h_sub       — subtitlu opțional (HTML permis)
 */
$h_eticheta = $h_eticheta ?? '';
$h_titlu    = $h_titlu ?? '';
$h_sub      = $h_sub ?? '';
?>
<section class="hero-soft hero-soft--pagina">
  <div class="hero-soft__planta hero-soft__planta--sus"><?= view('public/_botanic') ?></div>

  <svg class="hero-soft__stele" viewBox="0 0 1440 500" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
    <g fill="var(--salvie)" opacity="0.5">
      <circle cx="250" cy="90" r="4"/><circle cx="300" cy="64" r="2.5"/>
      <circle cx="1180" cy="330" r="4"/><circle cx="1130" cy="360" r="2.5"/>
    </g>
    <path fill="var(--lavanda)" opacity="0.75" d="M1250 100 l6 14 14 6 -14 6 -6 14 -6 -14 -14 -6 14 -6 Z"/>
  </svg>

  <div class="hero-soft__interior reveal vizibil">
    <p class="eticheta"><?= e($h_eticheta) ?></p>
    <h1><?= e($h_titlu) ?></h1>
    <?php if ($h_sub !== ''): ?>
      <p class="hero-soft__sub"><?= $h_sub ?></p>
    <?php endif ?>
  </div>
</section>
