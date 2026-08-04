<?php
/** Pagina de acasa. Variabile: $articole, $faq, $psihologi */
$pretIndividual = setare('pret_individual', '250');
$pretOnline     = setare('pret_online', '220');
$durataCunoastere = setare('durata_cunoastere', '20');
$psihologi = $psihologi ?? [];
?>

<!-- Hero soft, luminos, cu forme organice și plante -->
<section class="hero-soft">
  <div class="hero-soft__planta hero-soft__planta--sus"><?= view('public/_botanic') ?></div>
  <div class="hero-soft__planta hero-soft__planta--jos"><?= view('public/_botanic') ?></div>

  <svg class="hero-soft__stele" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
    <g fill="var(--salvie)" opacity="0.5">
      <circle cx="250" cy="120" r="4"/><circle cx="300" cy="92" r="2.5"/>
      <circle cx="1180" cy="520" r="4"/><circle cx="1130" cy="560" r="2.5"/>
    </g>
    <path fill="var(--teracota)" opacity="0.75" d="M1240 130 l6 14 14 6 -14 6 -6 14 -6 -14 -14 -6 14 -6 Z"/>
    <path fill="var(--bleu)" opacity="0.5" d="M170 640 l5 11 11 5 -11 5 -5 11 -5 -11 -11 -5 11 -5 Z"/>
  </svg>

  <div class="hero-soft__interior reveal vizibil">
    <p class="eticheta">Cabinet de psihologie · Timișoara</p>
    <h1><?= e(setare('titlu_acasa', 'Nu caut vinovatul, caut tiparul.')) ?></h1>
    <p class="hero-soft__sub">
      Nu te-ai stricat tu. De multe ori s-a rupt echilibrul din jurul tău — în
      familie, în cuplu, la muncă. Suntem aici să-l privim împreună.
    </p>
    <div class="hero-soft__actiuni">
      <a class="buton buton--principal" href="<?= e(url('contact')) ?>">
        Programează o ședință de cunoaștere
      </a>
      <span class="hero-soft__meta">Gratuită, <?= e($durataCunoastere) ?> de minute, fără nicio obligație.</span>
    </div>
  </div>
</section>

<!-- Manifest scurt, sub hero: intai asociatia, apoi ce facem -->
<section class="sectiune reveal">
  <p class="introducere">
    <strong><?= e(setare('cabinet_nume', 'Adam și Babotan')) ?></strong> este o
    societate civilă profesională de psihologie din Timișoara. Lucrăm cu adulți și
    adolescenți care trec prin anxietate, depresie sau epuizare și oferim evaluare
    și documentație psihologică — sistemic, adică privind tiparele dintre tine și
    contextul din care faci parte, nu un simptom rupt de tot restul.
  </p>
</section>

<div class="divizor"><?= grafic('val') ?></div>

<!-- Ai ajuns aici pentru ca... -->
<section class="sectiune reveal">
  <?= grafic('frunza', 'g-flotant g-flotant--dreapta-sus') ?>
  <?= grafic('puncte', 'g-flotant g-flotant--stanga-jos') ?>
  <p class="eticheta">Ai ajuns aici pentru că</p>
  <ul class="recunoasteri">
    <li>
      <span class="iconita-badge"><?= iconita('respiro') ?></span>
      <span>Te trezești dimineața deja obosit, și nu mai știi de când.</span>
    </li>
    <li>
      <span class="iconita-badge iconita-badge--lavanda"><?= iconita('coplesire') ?></span>
      <span>Faci totul „bine”, dar simți că nu mai e nimeni care să te întrebe pe tine ce faci.</span>
    </li>
    <li>
      <span class="iconita-badge iconita-badge--azur"><?= iconita('conexiune') ?></span>
      <span>Aceeași ceartă se repetă, cu oameni diferiți, și începi să te întrebi dacă nu cumva e ceva la tine.</span>
    </li>
    <li>
      <span class="iconita-badge"><?= iconita('ganduri') ?></span>
      <span>Gândurile nu se opresc seara, iar dimineața o iau de la capăt.</span>
    </li>
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

  <blockquote class="citat">
    <?= grafic('ghilimea') ?>
    <p>Nu te-ai stricat tu. S-a rupt echilibrul din jurul tău.</p>
    <cite>— abordarea noastră, pe scurt</cite>
  </blockquote>

  <p style="margin-top: var(--s3)">
    <a href="<?= e(url('cum-functioneaza')) ?>">Vezi cum decurge, mai în detaliu →</a>
  </p>
</section>

<!-- Echipa, pe scurt -->
<?php if (!empty($psihologi)): ?>
<section class="sectiune reveal">
  <p class="eticheta">Membrii cabinetului</p>
  <h2>Cele două psiholoage</h2>
  <p style="margin-top: var(--s3)">
    Sub această formă de exercitare lucrează două psiholoage. Fiecare are formarea
    și specializările ei, verificabile prin codul CPR în registrul Colegiului
    Psihologilor — dar împărtășesc aceeași abordare sistemică și caldă.
  </p>
  <div class="servicii" style="margin-top: var(--s4)">
    <?php foreach ($psihologi as $idx => $p): ?>
      <div class="serviciu">
        <span class="iconita-badge <?= $idx % 2 ? 'iconita-badge--lavanda' : 'iconita-badge--azur' ?> serviciu__icon"><?= iconita($idx % 2 ? 'echilibru' : 'sprijin') ?></span>
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

<div class="divizor"><?= grafic('val') ?></div>

<div class="reveal">
<?= view('public/_cta') ?>
</div>
