<?php /** 404 */ ?>
<section class="sectiune">
  <p class="eticheta">Eroare 404</p>
  <h1>Pagina asta nu există</h1>
  <p class="introducere" style="margin-top: var(--s3)">
    Probabil un link vechi sau o adresă scrisă greșit. Nu e nimic stricat la tine.
  </p>
  <p style="margin-top: var(--s4)">
    <a class="buton buton--principal" href="<?= e(url()) ?>">Înapoi la pagina principală</a>
  </p>
  <p style="margin-top: var(--s3)">
    Sau mergi direct la <a href="<?= e(url('articole')) ?>">articole</a>,
    <a href="<?= e(url('servicii')) ?>">servicii</a> ori
    <a href="<?= e(url('contact')) ?>">contact</a>.
  </p>
</section>
