<?php /** 404 — pagina inexistenta, dar cu un strop de grafica din kit. */ ?>
<section class="sectiune negasit reveal vizibil">
  <div class="negasit__grafic" aria-hidden="true">
    <?= grafic('blob') ?>
    <div class="negasit__planta"><?= view('public/_botanic') ?></div>
    <?= grafic('frunza', 'negasit__frunza') ?>
  </div>

  <div class="negasit__text">
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
  </div>
</section>
