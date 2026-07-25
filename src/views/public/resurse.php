<?php
/** Resurse. Butonul cere prin email — fara checkout in acest build. */
$email = (string) config('site', 'email');
?>

<section class="sectiune">
  <p class="eticheta">Resurse</p>
  <h1>Jurnale și ghiduri de lucru</h1>
  <p class="introducere" style="margin-top: var(--s3)">
    Materiale pe care le poți folosi singur sau în paralel cu terapia. Deocamdată
    se cer prin email — îmi scrii, îți răspund cu tot ce e nevoie.
  </p>
</section>

<section class="sectiune">
  <?php if (empty($resurse)): ?>
    <p>Momentan nu e nimic aici. Revino curând.</p>
  <?php else: ?>
    <div class="servicii">
      <?php foreach ($resurse as $r): ?>
        <div class="serviciu">
          <h3><?= e($r['titlu']) ?></h3>
          <?php if ((float) $r['pret'] > 0): ?>
            <p class="serviciu__pret"><?= e(number_format((float) $r['pret'], 0, ',', '.')) ?> lei</p>
          <?php else: ?>
            <p class="serviciu__pret">Gratuit</p>
          <?php endif ?>
          <?php foreach (explode("\n\n", $r['descriere']) as $paragraf): ?>
            <p><?= e($paragraf) ?></p>
          <?php endforeach ?>
          <?php
            // mailto prefilat: subiectul si corpul contin deja titlul resursei,
            // ca ea sa nu fie nevoita sa scrie de la zero.
            $subiect = rawurlencode('Cerere resursă: ' . $r['titlu']);
            $corp = rawurlencode("Bună ziua,\n\nAș dori resursa „" . $r['titlu'] . "”.\n\nMulțumesc,\n");
          ?>
          <p class="fara-margine-jos" style="margin-top: var(--s3)">
            <a class="buton buton--secundar"
               href="mailto:<?= e($email) ?>?subject=<?= $subiect ?>&body=<?= $corp ?>">
              Cere prin email
            </a>
          </p>
        </div>
      <?php endforeach ?>
    </div>
  <?php endif ?>
</section>

<?= view('public/_cta') ?>
