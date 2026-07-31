<?php
/**
 * Echipa — o secțiune per psihologă. Variabile: $psihologi (cu 'specializari').
 *
 * Nu combinăm biografiile într-un singur text: fiecare are propriul paragraf,
 * propria specializare, propriul regim. Regimul se afișează corect și onest —
 * „autonom" sau „sub supervizare" — fără să ascundem, dar fără accent negativ.
 */
$psihologi = $psihologi ?? [];
?>

<?= view('public/_hero', [
  'h_eticheta' => 'Echipa',
  'h_titlu'    => 'Cine suntem',
  'h_sub'      => 'Suntem două psiholoage care lucrează sub aceeași formă de exercitare — '
    . e(setare('cabinet_entitate', 'ADAM ȘI BABOTAN, Societate civilă profesională de psihologie'))
    . '. Fiecare are formarea și specializările ei; le găsești mai jos, cu codul CPR verificabil în registrul Colegiului Psihologilor.',
]) ?>

<?php foreach ($psihologi as $i => $p): ?>
<section class="sectiune reveal">
  <?= grafic('puncte', 'g-flotant ' . ($i % 2 ? 'g-flotant--stanga-jos' : 'g-flotant--dreapta-sus')) ?>
  <p class="eticheta">Psihologă</p>
  <h2><?= e($p['nume']) ?></h2>
  <p class="serviciu__durata" style="margin-top: var(--s1)"><?= e($p['titlu_scurt']) ?></p>

  <!-- Acreditare, cu regimul corect al fiecarei specializari -->
  <div class="acreditare" style="margin-top: var(--s3)">
    <p class="meta fara-margine-jos">
      Cod personal CPR <strong><?= e($p['cod_cpr']) ?></strong> ·
      Județ <?= e($p['judet']) ?> · Filiala <?= e($p['filiala']) ?>
    </p>
    <?php if (!empty($p['specializari'])): ?>
      <ul class="specializari">
        <?php foreach ($p['specializari'] as $s): ?>
          <li>
            <span class="specializare__nume"><?= e($s['nume']) ?></span>
            <span class="specializare__nivel"><?= e($s['nivel']) ?></span>
            <span class="regim regim--<?= e($s['regim']) ?>"><?= e(Psiholog::regimText($s['regim'])) ?></span>
          </li>
        <?php endforeach ?>
      </ul>
    <?php endif ?>
  </div>

  <!-- Biografia, din Markdown -->
  <div class="proza" style="margin-top: var(--s4)">
    <?= Markdown::toHtml($p['bio']) ?>
  </div>
</section>
<?php endforeach ?>

<!-- Nota despre regimul de supervizare, o data, clar -->
<section class="sectiune reveal">
  <div class="nota-info pe-tot-ecranul">
    <p class="fara-margine-jos meta">
      <strong>Ce înseamnă „sub supervizare”:</strong> la nivel de psiholog
      practicant, o parte din specializări se exercită sub îndrumarea unui
      psiholog specialist. E statutul normal prevăzut de Colegiul Psihologilor
      și înseamnă că munca e sprijinită de un cadru de verificare — nu o
      limitare a calității, ci o garanție în plus.
    </p>
  </div>
</section>

<?= view('public/_cta') ?>
