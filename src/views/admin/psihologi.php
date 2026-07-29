<?php
/**
 * Editor pentru biografiile și specializările psihologilor. Variabile: $psihologi.
 *
 * Nu se adaugă/șterg psiholoage din admin (sunt fixe: două), ci doar se editează.
 * Specializările se rescriu în bloc: golește un rând ca să-l ștergi.
 */
?>
<div class="admin-titlu-rand">
  <h1>Psihologi</h1>
</div>
<p class="meta" style="max-width:54ch; margin-bottom:var(--s4)">
  Biografiile (în Markdown), acreditarea și specializările fiecărei psiholoage.
  Datele apar pe pagina publică <strong>Echipa</strong>. Atenție la regim
  (autonom / sub supervizare) — trebuie să rămână corect.
</p>

<form method="post" action="<?= e(url('admin/psihologi')) ?>" class="admin-form">
  <?= Auth::campCsrf() ?>

  <?php foreach ($psihologi as $p): ?>
    <?php $id = (int) $p['id']; ?>
    <fieldset style="border:1px solid var(--n-300); border-radius:var(--raza-mediu); padding:var(--s4); margin-bottom:var(--s4)">
      <legend style="font-family:var(--font-display); font-size:var(--t-h3); padding:0 var(--s2)"><?= e($p['nume']) ?></legend>

      <div class="admin-rand admin-rand--2">
        <div class="admin-camp">
          <label for="p-nume-<?= $id ?>">Nume</label>
          <input type="text" id="p-nume-<?= $id ?>" name="psiholog[<?= $id ?>][nume]" value="<?= e($p['nume']) ?>">
        </div>
        <div class="admin-camp">
          <label for="p-titlu-<?= $id ?>">Titlu scurt</label>
          <input type="text" id="p-titlu-<?= $id ?>" name="psiholog[<?= $id ?>][titlu_scurt]" value="<?= e($p['titlu_scurt']) ?>">
        </div>
      </div>

      <div class="admin-rand admin-rand--2" style="margin-top:var(--s3)">
        <div class="admin-camp">
          <label for="p-cpr-<?= $id ?>">Cod CPR</label>
          <input type="text" id="p-cpr-<?= $id ?>" name="psiholog[<?= $id ?>][cod_cpr]" value="<?= e($p['cod_cpr']) ?>">
        </div>
        <div class="admin-rand admin-rand--2">
          <div class="admin-camp">
            <label for="p-judet-<?= $id ?>">Județ</label>
            <input type="text" id="p-judet-<?= $id ?>" name="psiholog[<?= $id ?>][judet]" value="<?= e($p['judet']) ?>">
          </div>
          <div class="admin-camp">
            <label for="p-fil-<?= $id ?>">Filiala</label>
            <input type="text" id="p-fil-<?= $id ?>" name="psiholog[<?= $id ?>][filiala]" value="<?= e($p['filiala']) ?>">
          </div>
        </div>
      </div>

      <div class="admin-camp" style="margin-top:var(--s3)">
        <label for="p-bio-<?= $id ?>">Biografie (Markdown)</label>
        <textarea id="p-bio-<?= $id ?>" name="psiholog[<?= $id ?>][bio]" rows="10"><?= e($p['bio']) ?></textarea>
        <span class="ajutor">Titluri cu <code>##</code>, liste cu <code>-</code>, <code>**îngroșat**</code>. Apare pe pagina Echipa.</span>
      </div>

      <!-- Specializari -->
      <fieldset style="border:1px dashed var(--n-400); border-radius:var(--raza-mediu); padding:var(--s3); margin-top:var(--s3)">
        <legend style="font-size:var(--t-mic); font-weight:700; padding:0 var(--s1)">Specializări</legend>
        <?php
          $spec = $p['specializari'];
          // Un rand gol in plus, pentru a adauga o specializare noua.
          $spec[] = ['nume' => '', 'nivel' => '', 'regim' => 'supervizare'];
        ?>
        <?php foreach ($spec as $i => $s): ?>
          <div class="admin-rand admin-rand--2" style="margin-bottom:var(--s2)">
            <div class="admin-camp">
              <label for="s-nume-<?= $id ?>-<?= $i ?>">Specializare</label>
              <input type="text" id="s-nume-<?= $id ?>-<?= $i ?>" name="psiholog[<?= $id ?>][spec][<?= $i ?>][nume]" value="<?= e($s['nume']) ?>" placeholder="ex. Psihologie clinică">
            </div>
            <div class="admin-rand admin-rand--2">
              <div class="admin-camp">
                <label for="s-niv-<?= $id ?>-<?= $i ?>">Nivel</label>
                <input type="text" id="s-niv-<?= $id ?>-<?= $i ?>" name="psiholog[<?= $id ?>][spec][<?= $i ?>][nivel]" value="<?= e($s['nivel'] ?? '') ?>" placeholder="ex. Practicant">
              </div>
              <div class="admin-camp">
                <label for="s-reg-<?= $id ?>-<?= $i ?>">Regim</label>
                <select id="s-reg-<?= $id ?>-<?= $i ?>" name="psiholog[<?= $id ?>][spec][<?= $i ?>][regim]">
                  <option value="supervizare" <?= ($s['regim'] ?? '') === 'supervizare' ? 'selected' : '' ?>>sub supervizare</option>
                  <option value="autonom" <?= ($s['regim'] ?? '') === 'autonom' ? 'selected' : '' ?>>autonom</option>
                </select>
              </div>
            </div>
          </div>
        <?php endforeach ?>
        <span class="ajutor">Golește numele unei specializări ca s-o ștergi. Rândul gol de jos adaugă una nouă.</span>
      </fieldset>

      <label style="display:flex; gap:var(--s1); align-items:center; margin-top:var(--s3); font-size:var(--t-mic)">
        <input type="checkbox" name="psiholog[<?= $id ?>][activ]" <?= !empty($p['activ']) ? 'checked' : '' ?>>
        Activă (vizibilă pe site)
        <input type="hidden" name="psiholog[<?= $id ?>][ordine]" value="<?= (int) $p['ordine'] ?>">
      </label>
    </fieldset>
  <?php endforeach ?>

  <div class="admin-actiuni">
    <button type="submit" class="buton buton--principal">Salvează datele psihologilor</button>
  </div>
</form>
