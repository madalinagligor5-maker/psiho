<?php
/**
 * Servicii, pe DOUĂ grupe (§3.1 din brief):
 *   (a) terapie și sprijin emoțional — ton cald, sistemic
 *   (b) evaluare și documentație — ton mai instituțional, orientat pe proces
 *
 * Publicul celor două grupe e diferit: adultul anxios/epuizat pentru (a),
 * părinți / familii / adolescenți pentru (b). Nu forțăm tonul „ai ajuns aici
 * pentru că…" peste serviciile de evaluare.
 */
?>

<?= view('public/_hero', [
  'h_eticheta' => 'Servicii',
  'h_titlu'    => 'Ce putem face împreună',
  'h_sub'      => 'Lucrăm cu adulți, familii și, pentru evaluări și documentație, cu copii și '
    . 'adolescenți. Serviciile sunt împărțite în două: terapie și sprijin emoțional, '
    . 'respectiv evaluare și documentație psihologică.',
]) ?>

<!-- GRUPA A: terapie si sprijin emotional -->
<section class="sectiune grup-servicii reveal">
  <div class="grup-servicii__cap">
    <p class="eticheta">Terapie și sprijin emoțional</p>
    <h2>Când vrei să înțelegi și să schimbi ceva</h2>
    <p style="margin-top: var(--s3)">
      Pentru anxietate, depresie, epuizare, tipare care se repetă în relații.
      Aici lucrăm sistemic — la tine în contextul tău, nu la un simptom izolat.
    </p>
  </div>

  <div class="servicii">
    <div class="serviciu">
      <span class="iconita-badge iconita-badge--azur serviciu__icon"><?= iconita('respiro') ?></span>
      <h3>Ședință individuală, la cabinet</h3>
      <p class="serviciu__pret"><?= e(setare('pret_individual', '250')) ?> lei</p>
      <p class="serviciu__durata"><?= e(setare('durata_individual', '50')) ?> de minute</p>
      <p class="fara-margine-jos">Într-un spațiu liniștit, care nu e nici casa, nici biroul tău. Pentru cine are nevoie să iasă din context ca să se poată gândi.</p>
    </div>
    <div class="serviciu">
      <span class="iconita-badge iconita-badge--azur serviciu__icon"><?= iconita('comunicare') ?></span>
      <h3>Ședință online</h3>
      <p class="serviciu__pret"><?= e(setare('pret_online', '220')) ?> lei</p>
      <p class="serviciu__durata"><?= e(setare('durata_online', '50')) ?> de minute · video securizat</p>
      <p class="fara-margine-jos">Aceeași ședință, de oriunde ai un colț liniștit. Pentru program imprevizibil sau drum lung.</p>
    </div>
    <div class="serviciu">
      <span class="iconita-badge iconita-badge--azur serviciu__icon"><?= iconita('conexiune') ?></span>
      <h3>Psihoterapie de familie</h3>
      <p class="serviciu__pret">[COMPLETEZ EU: preț]</p>
      <p class="serviciu__durata">[COMPLETEZ EU: durată]</p>
      <p class="autor-eticheta">Cu Adam Nicoleta-Cristina · psihoterapie de familie, sub supervizare</p>
      <p class="fara-margine-jos">Când ceea ce doare la o persoană se înțelege abia privind relațiile din jur — cuplu, părinți și copii, familia extinsă.</p>
    </div>
  </div>
</section>

<div class="divizor"><?= grafic('val') ?></div>

<!-- GRUPA B: evaluare si documentatie -->
<section class="sectiune grup-servicii reveal">
  <div class="grup-servicii__cap">
    <p class="eticheta">Evaluare și documentație</p>
    <h2>Când ai nevoie de o evaluare sau de acte oficiale</h2>
    <p style="margin-top: var(--s3)">
      Servicii orientate pe proces și pe documente. Aici publicul e diferit —
      părinți de copii cu cerințe educaționale speciale, familii care au nevoie
      de documentație pentru comisii, adulți și adolescenți la o decizie de carieră.
    </p>
    <p class="autor-eticheta">Oferite de Adam Nicoleta-Cristina · psihologie clinică, regim autonom</p>
  </div>

  <div class="servicii">
    <div class="serviciu">
      <span class="iconita-badge iconita-badge--lavanda serviciu__icon"><?= iconita('constientizare') ?></span>
      <h3>Evaluare psihologică</h3>
      <p class="serviciu__pret">[COMPLETEZ EU: preț]</p>
      <p class="fara-margine-jos"><strong>Ce presupune:</strong> una sau mai multe întâlniri, instrumente standardizate, discuția rezultatelor. <strong>Ce rezultă:</strong> un raport de evaluare psihologică.</p>
    </div>
    <div class="serviciu">
      <span class="iconita-badge iconita-badge--lavanda serviciu__icon"><?= iconita('sprijin') ?></span>
      <h3>Fișe CES și fișe de încadrare în grad</h3>
      <p class="serviciu__pret">[COMPLETEZ EU: preț]</p>
      <p class="fara-margine-jos"><strong>Pentru cine:</strong> copii și adolescenți care au nevoie de documentație psihologică pentru comisiile de evaluare (cerințe educaționale speciale, încadrare în grad).</p>
    </div>
    <div class="serviciu">
      <span class="iconita-badge iconita-badge--lavanda serviciu__icon"><?= iconita('directie') ?></span>
      <h3>Orientare în carieră</h3>
      <p class="serviciu__pret">[COMPLETEZ EU: preț]</p>
      <p class="serviciu__durata">Pentru adulți și adolescenți</p>
      <p class="fara-margine-jos"><strong>Ce presupune:</strong> evaluarea intereselor și aptitudinilor, discuția opțiunilor realiste. Pentru cine e la o răscruce și vrea o hartă, nu un răspuns de-a gata.</p>
    </div>
  </div>
</section>

<section class="sectiune reveal">
  <p class="eticheta">Bine de știut</p>
  <h2>Câteva lămuriri</h2>
  <p style="margin-top: var(--s3)">
    <strong>Cum plătesc?</strong> La sfârșitul ședinței, prin transfer sau
    numerar. Pentru online, transferul se face în ziua ședinței.
  </p>
  <p>
    <strong>Anulări:</strong> cu mai puțin de 24 de ore înainte, ședința se
    tarifează, pentru că intervalul rămâne blocat.
  </p>
  <p>
    <strong>Cu cine lucrezi?</strong> Pe pagina <a href="<?= e(url('echipa')) ?>">Echipa</a>
    vezi ce oferă fiecare psihologă. La <a href="<?= e(url('contact')) ?>">contact</a>
    poți spune dacă ai o preferință.
  </p>
</section>

<?= view('public/_cta') ?>
