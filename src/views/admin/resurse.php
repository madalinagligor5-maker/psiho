<?php /** Editor resurse. Variabile: $resurse */ ?>
<div class="admin-titlu-rand">
  <h1>Resurse</h1>
</div>
<p class="meta" style="max-width:52ch; margin-bottom:var(--s4)">
  Produsele digitale de pe pagina Resurse. Golește titlul ca să ștergi o resursă.
  Pune prețul 0 pentru „gratuit”.
</p>

<form method="post" action="<?= e(url('admin/resurse')) ?>" class="admin-form">
  <?= Auth::campCsrf() ?>

  <?php
    $randuri = $resurse;
    $randuri[] = ['id' => 'nou1', 'titlu' => '', 'descriere' => '', 'pret' => '0', 'ordine' => count($resurse) + 1, 'activ' => 1];
  ?>

  <?php foreach ($randuri as $r): ?>
    <?php $id = e((string) $r['id']); ?>
    <fieldset style="border:1px solid var(--n-300); border-radius:var(--raza-mediu); padding:var(--s3); margin-bottom:var(--s3)">
      <div class="admin-camp" style="margin-bottom:var(--s2)">
        <label for="res-t-<?= $id ?>">Titlu</label>
        <input type="text" id="res-t-<?= $id ?>" name="resurse[<?= $id ?>][titlu]" value="<?= e($r['titlu']) ?>">
      </div>
      <div class="admin-camp" style="margin-bottom:var(--s2)">
        <label for="res-d-<?= $id ?>">Descriere</label>
        <textarea id="res-d-<?= $id ?>" name="resurse[<?= $id ?>][descriere]" rows="3"><?= e($r['descriere']) ?></textarea>
      </div>
      <div class="admin-rand admin-rand--2">
        <div class="admin-camp">
          <label for="res-p-<?= $id ?>">Preț (lei)</label>
          <input type="number" step="1" id="res-p-<?= $id ?>" name="resurse[<?= $id ?>][pret]" value="<?= e((string) (int) $r['pret']) ?>" style="max-width:8rem">
        </div>
        <div class="admin-camp">
          <label for="res-o-<?= $id ?>">Ordine</label>
          <input type="number" id="res-o-<?= $id ?>" name="resurse[<?= $id ?>][ordine]" value="<?= (int) $r['ordine'] ?>" style="max-width:6rem">
        </div>
      </div>
      <label style="display:flex; gap:var(--s1); align-items:center; margin-top:var(--s2); font-size:var(--t-mic)">
        <input type="checkbox" name="resurse[<?= $id ?>][activ]" <?= !empty($r['activ']) ? 'checked' : '' ?>>
        Activă (vizibilă pe site)
      </label>
    </fieldset>
  <?php endforeach ?>

  <div class="admin-actiuni">
    <button type="submit" class="buton buton--principal">Salvează resursele</button>
  </div>
</form>
