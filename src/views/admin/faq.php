<?php
/** Editor FAQ. Variabile: $intrebari. Un rand gol la final adauga o intrebare noua. */
?>
<div class="admin-titlu-rand">
  <h1>Întrebări frecvente</h1>
</div>
<p class="meta" style="max-width:52ch; margin-bottom:var(--s4)">
  Golește textul unei întrebări ca s-o ștergi. Cele marcate „pe prima pagină”
  apar și pe acasă, într-o formă scurtă.
</p>

<form method="post" action="<?= e(url('admin/faq')) ?>" class="admin-form" data-repeta>

  <?php
    // Intrebarile existente, plus un rand gol pentru una noua.
    $randuri = $intrebari;
    $randuri[] = ['id' => 'nou1', 'intrebare' => '', 'raspuns' => '', 'ordine' => count($intrebari) + 1, 'afisare' => 'toate', 'activ' => 1];
  ?>

  <?php foreach ($randuri as $f): ?>
    <?php $id = e((string) $f['id']); ?>
    <fieldset style="border:1px solid var(--n-300); border-radius:var(--raza-mediu); padding:var(--s3); margin-bottom:var(--s3)">
      <div class="admin-camp" style="margin-bottom:var(--s2)">
        <label for="faq-i-<?= $id ?>">Întrebare</label>
        <input type="text" id="faq-i-<?= $id ?>" name="faq[<?= $id ?>][intrebare]" value="<?= e($f['intrebare']) ?>">
      </div>
      <div class="admin-camp" style="margin-bottom:var(--s2)">
        <label for="faq-r-<?= $id ?>">Răspuns</label>
        <textarea id="faq-r-<?= $id ?>" name="faq[<?= $id ?>][raspuns]" rows="3"><?= e($f['raspuns']) ?></textarea>
        <span class="ajutor">Lasă un rând gol între paragrafe.</span>
      </div>
      <div class="admin-rand admin-rand--2">
        <div class="admin-camp">
          <label for="faq-o-<?= $id ?>">Ordine</label>
          <input type="number" id="faq-o-<?= $id ?>" name="faq[<?= $id ?>][ordine]" value="<?= (int) $f['ordine'] ?>" style="max-width:6rem">
        </div>
        <div class="admin-camp">
          <label for="faq-a-<?= $id ?>">Unde apare</label>
          <select id="faq-a-<?= $id ?>" name="faq[<?= $id ?>][afisare]">
            <option value="toate" <?= $f['afisare'] === 'toate' ? 'selected' : '' ?>>Doar în lista completă</option>
            <option value="acasa" <?= $f['afisare'] === 'acasa' ? 'selected' : '' ?>>Și pe prima pagină</option>
          </select>
        </div>
      </div>
      <label style="display:flex; gap:var(--s1); align-items:center; margin-top:var(--s2); font-size:var(--t-mic)">
        <input type="checkbox" name="faq[<?= $id ?>][activ]" <?= !empty($f['activ']) ? 'checked' : '' ?>>
        Activă (vizibilă pe site)
      </label>
    </fieldset>
  <?php endforeach ?>

  <div class="admin-actiuni">
    <button type="submit" class="buton buton--principal">Salvează întrebările</button>
  </div>
</form>
