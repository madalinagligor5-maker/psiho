<?php
/** Pagina de acasa. Variabile: $articole, $faq */
$pretIndividual = setare('pret_individual', '250');
$pretOnline     = setare('pret_online', '220');
$durataCunoastere = setare('durata_cunoastere', '20');
?>

<section class="sectiune">
  <p class="eticheta">Psihoterapie sistemică</p>
  <h1><?= e(setare('titlu_acasa', 'Nu caut vinovatul, caut tiparul.')) ?></h1>
  <p class="introducere" style="margin-top: var(--s3)">
    Sunt psiholog clinician. Lucrez cu oameni care trec prin anxietate, depresie
    sau epuizare — nu tratând un simptom rupt de tot restul, ci uitându-mă la
    tiparele dintre tine și oamenii și locurile din care faci parte.
  </p>
  <div style="margin-top: var(--s4)">
    <a class="buton buton--principal" href="<?= e(url('contact')) ?>">
      Programează o ședință de cunoaștere
    </a>
    <p class="meta" style="margin-top: var(--s2)">
      Gratuită, <?= e($durataCunoastere) ?> de minute, fără nicio obligație.
    </p>
  </div>
</section>

<!-- Ai ajuns aici pentru ca... — recunoasteri la persoana a doua, fara etichete clinice -->
<section class="sectiune">
  <p class="eticheta">Ai ajuns aici pentru că</p>
  <ul class="recunoasteri">
    <li>Te trezești dimineața deja obosit, și nu mai știi de când.</li>
    <li>Faci totul „bine”, dar simți că nu mai e nimeni care să te întrebe pe tine ce faci.</li>
    <li>Aceeași ceartă se repetă, cu oameni diferiți, și începi să te întrebi dacă nu cumva e ceva la tine.</li>
    <li>Gândurile nu se opresc seara, iar dimineața o iau de la capăt.</li>
  </ul>
  <p style="margin-top: var(--s4)">
    Niciuna dintre acestea nu e un diagnostic. Sunt feluri în care viața strânge,
    și pentru fiecare există un fir care poate fi urmărit.
  </p>
</section>

<!-- Cum lucrez -->
<section class="sectiune">
  <p class="eticheta">Cum lucrez</p>
  <h2>Simptomul e vârful. Mă interesează ce ține de el</h2>
  <p style="margin-top: var(--s3)">
    Terapia sistemică pornește de la o idee simplă: nimeni nu se simte rău
    într-un vid. Anxietatea, oboseala, felul în care reacționezi — toate există
    într-o rețea de relații: familia în care ai crescut, cuplul de acum, munca,
    rolurile pe care le-ai preluat fără să le alegi.
  </p>
  <p>
    Nu caut ce e „în neregulă cu tine”. Caut tiparul care se repetă și care,
    cândva, a fost cea mai bună soluție pe care ai găsit-o. Când tiparul devine
    vizibil, încetează să te mai conducă din umbră.
  </p>
  <p style="margin-top: var(--s3)">
    <a href="<?= e(url('cum-functioneaza')) ?>">Vezi cum decurge, mai în detaliu →</a>
  </p>
</section>

<!-- Servicii cu preturi vizibile (§5: preturile se afiseaza) -->
<section class="sectiune">
  <p class="eticheta">Ședințe</p>
  <h2>Prețurile, de la început</h2>
  <p style="margin-top: var(--s3)">
    Le pun aici pentru că a fi nevoit să întrebi cât costă e, pentru mulți
    oameni, încă un motiv de amânat.
  </p>

  <div class="servicii" style="margin-top: var(--s4)">
    <div class="serviciu">
      <h3>Ședință individuală</h3>
      <p class="serviciu__pret"><?= e($pretIndividual) ?> lei</p>
      <p class="serviciu__durata"><?= e(setare('durata_individual', '50')) ?> de minute · la cabinet</p>
      <p class="fara-margine-jos">Un spațiu care nu e casa ta și nu e biroul tău. Pentru cine are nevoie să iasă din context ca să se poată gândi.</p>
    </div>
    <div class="serviciu">
      <h3>Ședință online</h3>
      <p class="serviciu__pret"><?= e($pretOnline) ?> lei</p>
      <p class="serviciu__durata"><?= e(setare('durata_online', '50')) ?> de minute · video</p>
      <p class="fara-margine-jos">Aceeași ședință, de oriunde ai un colț liniștit. Pentru program imprevizibil sau drum lung până la cabinet.</p>
    </div>
  </div>

  <p style="margin-top: var(--s4)">
    <a href="<?= e(url('servicii')) ?>">Toate detaliile despre ședințe →</a>
  </p>
</section>

<!-- Cei 4 pasi -->
<section class="sectiune">
  <p class="eticheta">De la gând la prima ședință</p>
  <h2>Patru pași, niciunul complicat</h2>

  <ol class="pasi" style="margin-top: var(--s4)">
    <li class="pas">
      <h3>Scrii, sau suni</h3>
      <p class="fara-margine-jos">Un formular scurt, un mesaj pe WhatsApp, un email. Nu ai nevoie să explici tot — un rând ajunge.</p>
    </li>
    <li class="pas">
      <h3>Ședința de cunoaștere</h3>
      <p class="fara-margine-jos">Douăzeci de minute, gratuit. Îmi spui ce cauți, îți spun cum lucrez. Fără angajament.</p>
    </li>
    <li class="pas">
      <h3>Decizi tu</h3>
      <p class="fara-margine-jos">Dacă ți se pare că ne potrivim, stabilim prima ședință propriu-zisă. Dacă nu, e la fel de în regulă.</p>
    </li>
    <li class="pas">
      <h3>Începem</h3>
      <p class="fara-margine-jos">La cabinet sau online, la un interval pe care îl alegem împreună.</p>
    </li>
  </ol>
</section>

<!-- Despre, pe scurt -->
<section class="sectiune">
  <p class="eticheta">Despre mine</p>
  <h2>Cine sunt</h2>
  <p style="margin-top: var(--s3)">
    Psiholog clinician acreditat de Colegiul Psihologilor din România (COPSI),
    în formare ca psihoterapeut sistemic. Lucrez cu adulți, individual, pe
    anxietate, depresie, burnout și pe tiparele care se repetă în relații.
  </p>
  <p>
    <span class="nota-margine">
      [COMPLETEZ EU: un rând–două despre de ce faci munca asta — e locul unde
      site-ul devine al tău, nu al oricui.]
    </span>
    Cred că oamenii nu vin la terapie ca să fie reparați, ci ca să fie însoțiți
    în timp ce înțeleg ceva ce până atunci nu au avut cu cine gândi.
  </p>
  <p style="margin-top: var(--s3)">
    <a href="<?= e(url('despre-mine')) ?>">Mai multe despre formare și felul în care lucrez →</a>
  </p>
</section>

<!-- Articole recente -->
<?php if (!empty($articole)): ?>
<section class="sectiune">
  <p class="eticheta">De citit</p>
  <h2>Articole recente</h2>
  <div class="articole" style="margin-top: var(--s4)">
    <?php foreach ($articole as $a): ?>
      <article class="articol-card">
        <?php if (!empty($a['categorie_nume'])): ?>
          <p class="meta"><?= e($a['categorie_nume']) ?> · <?= reading_time($a['continut']) ?> min de citit</p>
        <?php endif ?>
        <h3><a href="<?= e(url('articol/' . $a['slug'])) ?>"><?= e($a['titlu']) ?></a></h3>
        <p><?= e($a['rezumat']) ?></p>
      </article>
    <?php endforeach ?>
  </div>
  <p style="margin-top: var(--s4)">
    <a href="<?= e(url('articole')) ?>">Toate articolele →</a>
  </p>
</section>
<?php endif ?>

<!-- FAQ scurt -->
<?php if (!empty($faq)): ?>
<section class="sectiune">
  <p class="eticheta">Întrebări</p>
  <h2>Ce mă întreabă oamenii înainte să înceapă</h2>
  <div class="faq" style="margin-top: var(--s4)">
    <?php foreach ($faq as $f): ?>
      <details class="intrebare">
        <summary><?= e($f['intrebare']) ?></summary>
        <div class="intrebare__raspuns">
          <?php foreach (explode("\n\n", $f['raspuns']) as $paragraf): ?>
            <p><?= e($paragraf) ?></p>
          <?php endforeach ?>
        </div>
      </details>
    <?php endforeach ?>
  </div>
</section>
<?php endif ?>

<?= view('public/_cta') ?>
