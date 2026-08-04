<?php
/**
 * Echipa — o secțiune per psihologă. Variabile: $psihologi (cu 'specializari').
 *
 * Nu combinăm biografiile într-un singur text: fiecare are propriul paragraf,
 * propria specializare, propriul regim. Regimul se afișează corect și onest —
 * „autonom" sau „sub supervizare" — fără să ascundem, dar fără accent negativ.
 */
$psihologi = $psihologi ?? [];

// Curata notele editoriale [COMPLETEZ EU: ...] impreuna cu conectorul din fata
// ("la", "in", "cu"), ca sa nu ramana public un fragment ciuntit. Daca psihologa
// nu si-a transmis inca datele, aratam doar ce e confirmat + o nota neutra.
$curat = function (?string $t): string {
    $t = (string) $t;
    // 1) Un titlu Markdown urmat doar de un placeholder -> scoatem tot blocul,
    //    ca sa nu ramana un „## Formare" gol atarnand.
    $t = preg_replace('/\n[ \t]*#{1,6}[^\n]*\n\s*\[COMPLETEZ EU:[^\]]*\]/u', '', $t);
    // 2) Placeholder inline (ex. „…la [COMPLETEZ EU: …]") + conectorul din fata.
    $t = preg_replace('/[\s,–\-]*(?:la|în|cu)?\s*\[COMPLETEZ EU:[^\]]*\]/u', '', $t);
    return trim((string) $t);
};
$areText = fn(?string $t): bool => $curat($t) !== '';
?>

<?= view('public/_hero', [
  'h_eticheta' => 'Echipa',
  'h_titlu'    => 'Cabinetul și cine îl formează',
  'h_sub'      => e(setare('cabinet_entitate', 'ADAM ȘI BABOTAN, Societate civilă profesională de psihologie'))
    . ' este forma sub care profesăm, în Timișoara. Sub ea lucrează două psiholoage; '
    . 'fiecare are formarea și specializările ei — le găsești mai jos, cu codul CPR '
    . 'verificabil în registrul Colegiului Psihologilor.',
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
          <?php $sn = $curat($s['nume']); if ($sn === '') continue; ?>
          <li>
            <span class="specializare__nume"><?= e($sn) ?></span>
            <?php if ($areText($s['nivel'])): ?><span class="specializare__nivel"><?= e($curat($s['nivel'])) ?></span><?php endif ?>
            <span class="regim regim--<?= e($s['regim']) ?>"><?= e(Psiholog::regimText($s['regim'])) ?></span>
          </li>
        <?php endforeach ?>
      </ul>
    <?php endif ?>
  </div>

  <!-- Biografia. Curatam notele editoriale; daca dupa curatare nu ramane
       nimic real (psihologa nu si-a transmis inca datele), aratam o nota neutra. -->
  <?php $bioCurat = $curat($p['bio']); ?>
  <div class="proza" style="margin-top: var(--s4)">
    <?php if ($bioCurat === ''): ?>
      <p class="meta">Detaliile de formare și un cuvânt din partea
        <?= e(explode(' ', (string) $p['nume'])[1] ?? $p['nume']) ?> se adaugă în curând.</p>
    <?php else: ?>
      <?= Markdown::toHtml($bioCurat) ?>
    <?php endif ?>
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
