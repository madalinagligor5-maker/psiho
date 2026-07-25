<?php
/** Setari editabile. Variabile: $setari (grupate pe coloana `grup`) */
$grupuri = [];
foreach ($setari as $s) {
    $grupuri[$s['grup']][] = $s;
}
$numeGrup = [
    'preturi' => 'Prețuri și durate',
    'contact' => 'Date de contact',
    'texte'   => 'Texte',
    'general' => 'General',
];
?>
<div class="admin-titlu-rand">
  <h1>Setări</h1>
</div>
<p class="meta" style="max-width:48ch; margin-bottom:var(--s4)">
  Tot ce e aici apare direct pe site. Nu ai nevoie de nimeni ca să schimbi un preț
  sau o adresă.
</p>

<form method="post" action="<?= e(url('admin/setari')) ?>" class="admin-form">
  <?= Auth::campCsrf() ?>

  <?php foreach ($grupuri as $grup => $randuri): ?>
    <section style="margin-bottom:var(--s5)">
      <h2 style="font-size:var(--t-h3); margin-bottom:var(--s3)">
        <?= e($numeGrup[$grup] ?? ucfirst($grup)) ?>
      </h2>

      <?php foreach ($randuri as $s): ?>
        <div class="admin-camp" style="margin-bottom:var(--s3)">
          <label for="setare-<?= e($s['cheie']) ?>"><?= e($s['eticheta']) ?></label>
          <?php if ($s['tip'] === 'textarea'): ?>
            <textarea id="setare-<?= e($s['cheie']) ?>" name="setari[<?= e($s['cheie']) ?>]" rows="3"><?= e($s['valoare']) ?></textarea>
          <?php else: ?>
            <input type="<?= $s['tip'] === 'numar' ? 'number' : 'text' ?>"
                   id="setare-<?= e($s['cheie']) ?>"
                   name="setari[<?= e($s['cheie']) ?>]"
                   value="<?= e($s['valoare']) ?>"
                   <?= $s['tip'] === 'numar' ? 'style="max-width:10rem"' : '' ?>>
          <?php endif ?>
        </div>
      <?php endforeach ?>
    </section>
  <?php endforeach ?>

  <div class="admin-actiuni">
    <button type="submit" class="buton buton--principal">Salvează setările</button>
  </div>
</form>
