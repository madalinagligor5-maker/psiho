<?php
/** Pagina de acasa. Variabile: $articole, $faq, $psihologi */
$pretIndividual = setare('pret_individual', '250');
$pretOnline     = setare('pret_online', '220');
$durataCunoastere = setare('durata_cunoastere', '20');
$psihologi = $psihologi ?? [];
?>

<!-- Hero, cu ramura botanica in margine -->
<section class="sectiune hero">
  <div class="hero-botanic"><?= view('public/_botanic') ?></div>

  <p class="eticheta">Cabinet de psihologie · Timișoara</p>
  <h1><?= e(setare('titlu_acasa', 'Nu caut vinovatul, caut tiparul.')) ?></h1>
  <p class="introducere" style="margin-top: var(--s3)">
    Nu te-ai stricat tu. De multe ori s-a rupt echilibrul din jurul tău — în
    familie, în cuplu, la muncă. Suntem două psiholoage și lucrăm cu oameni care
    trec prin anxietate, depresie sau epuizare, uitându-ne la tiparele dintre
    tine și sistemele din care faci parte, nu la un simptom rupt de tot restul.
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

<!-- Ai ajuns aici pentru ca... -->
<section class="sectiune reveal">
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

<!-- Cum lucram -->
<section class="sectiune reveal">
  <p class="eticheta">Cum lucrăm</p>
  <h2>Simptomul e vârful. Ne interesează ce ține de el</h2>
  <p style="margin-top: var(--s3)">
    Terapia sistemică pornește de la o idee simplă: nimeni nu se simte rău
    într-un vid. Anxietatea, oboseala, felul în care reacționezi — toate există
    într-o rețea de relații: familia în care ai crescut, cuplul de acum, munca,
    rolurile pe care le-ai preluat fără să le alegi.
  </p>
  <p>
    Nu căutăm ce e „în neregulă cu tine”. Căutăm tiparul care se repetă și care,
    cândva, a fost cea mai bună soluție pe care ai găsit-o. Când tiparul devine
    vizibil, încetează să te mai conducă din umbră.
  </p>
  <p style="margin-top: var(--s3)">
    <a href="<?= e(url('cum-functioneaza')) ?>">Vezi cum decurge, mai în detaliu →</a>
  </p>
</section>

<!-- Echipa, pe scurt -->
<?php if (!empty($psihologi)): ?>
<section class="sectiune reveal">
  <p class="eticheta">Cine suntem</p>
  <h2>Două psiholoage, un cabinet</h2>
  <p style="margin-top: var(--s3)">
    <?= e(setare('cabinet_entitate', 'ADAM ȘI BABOTAN, Societate civilă profesională de psihologie')) ?>.
    Lucrăm fiecare cu specializarea ei, dar împărtășim aceeași abordare sistemică
    și caldă.
  </p>
  <div class="servicii" style="margin-top: var(--s4)">
    <?php foreach ($psihologi as $p): ?>
      <div class="serviciu">
        <h3><?= e($p['nume']) ?></h3>
        <p class="serviciu__durata"><?= e($p['titlu_scurt']) ?></p>
        <p class="fara-margine-jos meta">Cod CPR <?= e($p['cod_cpr']) ?> · Filiala <?= e($p['filiala']) ?></p>
      </div>
    <?php endforeach ?>
  </div>
  <p style="margin-top: var(--s4)">
    <a href="<?= e(url('echipa')) ?>">Despre fiecare dintre noi →</a>
  </p>
</section>
<?php endif ?>

<!-- Servicii cu preturi vizibile -->
<section class="sectiune reveal">
  <p class="eticheta">Ședințe</p>
  <h2>Prețurile, de la început</h2>
  <p style="margin-top: var(--s3)">
    Le punem aici pentru că a fi nevoit să întrebi cât costă e, pentru mulți
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
    <a href="<?= e(url('servicii')) ?>">Toate serviciile și prețurile →</a>
  </p>
</section>

<!-- Cei 4 pasi -->
<section class="sectiune reveal">
  <p class="eticheta">De la gând la prima ședință</p>
  <h2>Patru pași, niciunul complicat</h2>

  <ol class="pasi" style="margin-top: var(--s4)">
    <li class="pas">
      <h3>Scrii, sau suni</h3>
      <p class="fara-margine-jos">Un formular scurt, un mesaj pe WhatsApp, un email. Nu ai nevoie să explici tot — un rând ajunge.</p>
    </li>
    <li class="pas">
      <h3>Ședința de cunoaștere</h3>
      <p class="fara-margine-jos">Douăzeci de minute, gratuit. Ne spui ce cauți, îți spunem cum lucrăm. Fără angajament.</p>
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

<!-- Articole recente -->
<?php if (!empty($articole)): ?>
<section class="sectiune reveal">
  <p class="eticheta">De citit</p>
  <h2>Articole recente</h2>
  <div class="articole" style="margin-top: var(--s4)">
    <?php foreach ($articole as $a): ?>
      <article class="articol-card">
        <p class="meta">
          <?php if (!empty($a['categorie_nume'])): ?><?= e($a['categorie_nume']) ?> · <?php endif ?>
          <?= reading_time($a['continut']) ?> min de citit
        </p>
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
<section class="sectiune reveal">
  <p class="eticheta">Întrebări</p>
  <h2>Ce ne întreabă oamenii înainte să înceapă</h2>
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

<div class="reveal">
<?= view('public/_cta') ?>
</div>
