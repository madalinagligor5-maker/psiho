<?php /** Despre mine. Fotografie: spatiul cabinetului, niciodata portret. */ ?>

<section class="sectiune">
  <p class="eticheta">Despre mine</p>
  <h1>Cine sunt și de ce fac asta</h1>
  <p class="introducere" style="margin-top: var(--s3)">
    [COMPLETEZ EU: două–trei rânduri la persoana întâi despre cine ești. E cel
    mai citit paragraf al site-ului și singurul care nu poate fi scris în locul
    tău. Scrie-l cum ai vorbi, nu cum ai scrie un CV.]
  </p>
</section>

<section class="sectiune">
  <p class="eticheta">Formare</p>
  <h2>Pregătire și acreditare</h2>
  <span class="nota-margine">
    Codul de acreditare COPSI poate fi verificat public în registrul Colegiului.
  </span>
  <ul style="margin-top: var(--s3)">
    <li>Psiholog clinician, acreditat de Colegiul Psihologilor din România (COPSI), cod <?= e(setare('acreditare', '[COMPLETEZ EU]')) ?>.</li>
    <li>În formare ca psihoterapeut sistemic. [COMPLETEZ EU: la ce institut, în ce an.]</li>
    <li>[COMPLETEZ EU: studii — facultate, master, an absolvire.]</li>
    <li>[COMPLETEZ EU: formări suplimentare relevante, dacă ai.]</li>
  </ul>
  <p style="margin-top: var(--s3)" class="meta">
    Nu trec aici ani de experiență sau număr de clienți — le completez doar dacă
    sunt reale și verificabile.
  </p>
</section>

<section class="sectiune">
  <p class="eticheta">Felul în care lucrez</p>
  <h2>La ce te poți aștepta de la mine</h2>
  <p style="margin-top: var(--s3)">
    Nu dau sfaturi și nu împart oamenii în corecți și greșiți. Cred că vii la
    terapie cu propria expertiză despre viața ta — eu aduc o altă perspectivă și
    întrebările pe care poate nu ai avut cu cine să le gândești.
  </p>
  <p>
    Sunt directă, dar nu grăbită. Nu te presez să ajungi undeva anume până la o
    dată anume. Ritmul îl dai tu; eu mă asigur că nu ne pierdem pe drum.
  </p>
  <p>
    [COMPLETEZ EU: încă un rând despre stilul tău, în cuvintele tale.]
  </p>
</section>

<section class="sectiune">
  <p class="eticheta">Cabinetul</p>
  <h2>Unde ne vedem</h2>
  <p style="margin-top: var(--s3)">
    [COMPLETEZ EU: un rând despre spațiu — unde e, cum se ajunge, ce simți când
    intri.]
  </p>
  <figure style="margin: var(--s4) 0 0">
    <img src="<?= e(asset('assets/images/cabinet.webp')) ?>"
         alt="[COMPLETEZ EU: descrie fotografia cabinetului]"
         width="1200" height="800" loading="lazy"
         style="border-radius: var(--raza-mediu)">
  </figure>
</section>

<?= view('public/_cta') ?>
