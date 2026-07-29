<?php
/**
 * Ramura botanică — elementul-semnătură al site-ului (vezi DESIGN.md §4).
 *
 * Înlocuiește „Firul". Aceeași idee sistemică — o tulpină care se ramifică
 * lateral, nu doar coboară — dar în limbajul botanic al identității clientei
 * de pe @terapie.cu.cristina. Tulpina (linia structurală) e lizibilă; frunzele
 * sunt decorative. Se leagănă foarte lent; static la prefers-reduced-motion.
 *
 * Frunzele alternează stânga/dreapta ca o ramură reală, nu simetric.
 */
$puncte = [
    // [y pe tulpină, latură (1 = dreapta, -1 = stânga), ton]
    [382,  1, 'salvie'],
    [322, -1, 'cald'],
    [252,  1, 'salvie'],
    [192, -1, 'salvie'],
    [122,  1, 'cald'],
    [ 68, -1, 'salvie'],
];
?>
<svg class="botanic" viewBox="0 0 100 440" fill="none"
     xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
  <path class="botanic__tulpina"
        d="M50 436 C 44 384 56 352 50 302 C 44 252 58 222 50 172 C 44 122 56 92 50 42 C 48 26 50 16 50 8"/>

  <?php foreach ($puncte as [$y, $latura, $ton]): ?>
    <?php $rot = $latura === 1 ? -28 : -152; ?>
    <g class="botanic__grup" transform="translate(50 <?= $y ?>) rotate(<?= $rot ?>)">
      <path class="botanic__ram" d="M0 0 L 10 0"/>
      <path class="botanic__frunza<?= $ton === 'cald' ? ' botanic__frunza--cald' : '' ?>"
            d="M10 0 C 18 -7, 34 -6, 46 0 C 34 6, 18 7, 10 0 Z"/>
      <path class="botanic__vena" d="M13 0 Q 28 0, 43 0"/>
    </g>
  <?php endforeach ?>

  <!-- Câteva boabe mici, calde, în vârf. -->
  <circle class="botanic__bob" cx="50" cy="26" r="2.2"/>
  <circle class="botanic__bob" cx="46" cy="17" r="1.5"/>
  <circle class="botanic__bob" cx="54" cy="12" r="1.2"/>
</svg>
