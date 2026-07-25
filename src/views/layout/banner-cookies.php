<?php
/**
 * Bannerul de consimtamant.
 *
 * Nu se afiseaza deloc daca nu e configurata nicio analiza — un banner care
 * cere acordul pentru nimic e o fereastra pusa degeaba in fata cuiva care a
 * venit sa citeasca.
 *
 * Scripturile de analiza NU sunt in pagina. Se incarca din site.js abia dupa
 * accept. Pana atunci, browserul vizitatorului nu face niciun request catre
 * un tert.
 */
$analiza = config('analiza');
if (empty($analiza['domeniu'])) {
    return;
}
?>
<div class="cookies" id="banner-cookies" hidden
     role="dialog" aria-labelledby="cookies-titlu" aria-describedby="cookies-text">
  <div class="cookies__interior">

    <h2 class="cookies__titlu" id="cookies-titlu">Un singur lucru despre cookies</h2>

    <p class="cookies__text" id="cookies-text">
      Site-ul folosește doar ce e strict necesar ca să funcționeze. Separat, aș
      vrea să știu câți oameni ajung pe fiecare pagină — printr-un serviciu care
      nu te urmărește și nu construiește niciun profil. Poți refuza; site-ul
      funcționează identic.
    </p>

    <div class="cookies__optiuni">
      <label class="cookies__optiune">
        <input type="checkbox" checked disabled>
        <span>
          <strong>Necesare</strong> — sesiunea și protecția formularelor.
          Fără ele site-ul nu merge, deci nu se pot opri.
        </span>
      </label>

      <label class="cookies__optiune">
        <input type="checkbox" id="consimtamant-analiza">
        <span>
          <strong>Statistici</strong> — numărul de vizite pe pagină, fără
          cookies și fără date personale.
        </span>
      </label>
    </div>

    <div class="cookies__butoane">
      <button class="buton buton--principal" type="button" data-cookies="salveaza">
        Salvează alegerea
      </button>
      <button class="buton buton--secundar" type="button" data-cookies="refuza">
        Doar strictul necesar
      </button>
    </div>

    <p class="cookies__link">
      <a href="<?= e(url('politica-de-cookies')) ?>">Politica de cookies</a>
    </p>

  </div>
</div>
