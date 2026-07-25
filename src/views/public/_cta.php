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
