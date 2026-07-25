<?php /** Servicii si preturi. */ ?>

<section class="sectiune">
  <p class="eticheta">Servicii</p>
  <h1>Ședințe și prețuri</h1>
  <p class="introducere" style="margin-top: var(--s3)">
    Lucrez cu adulți, individual. Nu fac evaluări pentru instanțe, nu eliberez
    adeverințe și nu prescriu medicație — pentru asta e nevoie de un medic
    psihiatru, spre care te pot îndruma dacă e cazul.
  </p>
</section>

<section class="sectiune">
  <div class="servicii">
    <div class="serviciu">
      <h3>Ședință individuală, la cabinet</h3>
      <p class="serviciu__pret"><?= e(setare('pret_individual', '250')) ?> lei</p>
      <p class="serviciu__durata"><?= e(setare('durata_individual', '50')) ?> de minute</p>
      <p>Într-un spațiu gândit pentru asta — liniștit, care nu e nici casa, nici biroul tău.</p>
      <p class="fara-margine-jos"><strong>Se potrivește dacă:</strong> ai nevoie să ieși din contextul obișnuit ca să te poți gândi, sau acasă nu ai unde vorbi netulburat.</p>
    </div>

    <div class="serviciu">
      <h3>Ședință online</h3>
      <p class="serviciu__pret"><?= e(setare('pret_online', '220')) ?> lei</p>
      <p class="serviciu__durata"><?= e(setare('durata_online', '50')) ?> de minute · video securizat</p>
      <p>Aceeași ședință, de oriunde ai un colț liniștit și internet stabil.</p>
      <p class="fara-margine-jos"><strong>Se potrivește dacă:</strong> ai program imprevizibil, locuiești departe, sau drumul până la cabinet ar deveni un motiv de amânare.</p>
    </div>

    <div class="serviciu">
      <h3>Ședință de cunoaștere</h3>
      <p class="serviciu__pret">Gratuită</p>
      <p class="serviciu__durata"><?= e(setare('durata_cunoastere', '20')) ?> de minute · online sau telefonic</p>
      <p>O conversație scurtă înainte de orice angajament. Îmi spui ce cauți, îți spun cum lucrez.</p>
      <p class="fara-margine-jos"><strong>Pentru cine:</strong> oricine se gândește să înceapă și vrea întâi să vadă dacă ne potrivim.</p>
    </div>
  </div>
</section>

<section class="sectiune">
  <p class="eticheta">Bine de știut</p>
  <h2>Câteva lămuriri</h2>
  <p style="margin-top: var(--s3)">
    <strong>Cât de des?</strong> La început, de obicei săptămânal. După ce lucrurile
    se așază, rărim împreună.
  </p>
  <p>
    <strong>Cum plătesc?</strong> La sfârșitul ședinței, prin transfer sau numerar.
    Pentru online, transferul se face în ziua ședinței.
  </p>
  <p>
    <strong>Anulări:</strong> cu mai puțin de 24 de ore înainte, ședința se
    tarifează, pentru că intervalul rămâne blocat.
  </p>
  <p>
    <strong>Facturare:</strong> [COMPLETEZ EU: dacă emiți factură / chitanță și cum.]
  </p>
</section>

<?= view('public/_cta') ?>
