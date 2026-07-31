<?php
/**
 * Blocul de chemare la actiune, refolosit la finalul fiecarei pagini.
 *
 * Un singur CTA, peste tot acelasi: sedinta de cunoastere. Niciodata al doilea
 * buton concurent langa el.
 */
$durata = setare('durata_cunoastere', '20');
?>
<section class="sectiune">
  <div class="chemare pe-tot-ecranul">
    <div class="chemare__botanic"><?= view('public/_botanic') ?></div>
    <svg class="chemare__stele" viewBox="0 0 120 90" aria-hidden="true">
      <g fill="var(--salvie-inchis)" opacity="0.35">
        <circle cx="24" cy="20" r="3.2"/><circle cx="52" cy="12" r="2"/><circle cx="18" cy="44" r="1.8"/>
      </g>
      <path fill="var(--lavanda-text)" opacity="0.5" d="M70 22 l4 9 9 4 -9 4 -4 9 -4 -9 -9 -4 9 -4 Z"/>
    </svg>
    <h2>Nu trebuie să te hotărăști azi</h2>
    <p>
      O ședință de cunoaștere durează <?= e($durata) ?> de minute, e gratuită și nu te
      obligă la nimic. E doar o conversație în care afli cum lucrez și vezi dacă
      te simți în largul tău. De acolo, decizi tu.
    </p>
    <a class="buton buton--principal" href="<?= e(url('contact')) ?>">
      Programează o ședință de cunoaștere
    </a>
  </div>
</section>
