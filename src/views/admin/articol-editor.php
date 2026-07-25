<?php
/**
 * Editorul de articol. Variabile: $articol (sau null), $categorii
 *
 * Textarea „continut" ramane in pagina; editorul TipTap o imbunatateste si o
 * tine sincronizata. Fara JavaScript, textarea e vizibila si formularul merge.
 */
$a = $articol ?? null;
$esteNou = $a === null;
?>
<div class="admin-titlu-rand">
  <h1><?= $esteNou ? 'Articol nou' : 'Editează articol' ?></h1>
  <?php if (!$esteNou): ?>
    <a class="buton buton--secundar" href="<?= e(url('admin/previzualizare/' . $a['id'])) ?>" target="_blank" rel="noopener">Previzualizează ↗</a>
  <?php endif ?>
</div>

<form method="post" action="<?= e(url('admin/articole/salveaza')) ?>" class="admin-form" id="form-articol">
  <?= Auth::campCsrf() ?>
  <input type="hidden" name="id" value="<?= (int) ($a['id'] ?? 0) ?>">

  <div class="admin-camp">
    <label for="titlu">Titlu</label>
    <input type="text" id="titlu" name="titlu" required
           value="<?= e($a['titlu'] ?? '') ?>" data-genereaza-slug>
  </div>

  <div class="admin-rand admin-rand--slug">
    <div class="admin-camp">
      <label for="slug">Slug (adresa articolului)</label>
      <input type="text" id="slug" name="slug" value="<?= e($a['slug'] ?? '') ?>">
      <span class="ajutor">Se generează din titlu. Îl poți edita.</span>
    </div>
    <div class="admin-camp">
      <label for="categorie_id">Categorie</label>
      <select id="categorie_id" name="categorie_id">
        <option value="">Fără categorie</option>
        <?php foreach ($categorii as $c): ?>
          <option value="<?= (int) $c['id'] ?>" <?= (int) ($a['categorie_id'] ?? 0) === (int) $c['id'] ? 'selected' : '' ?>>
            <?= e($c['nume']) ?>
          </option>
        <?php endforeach ?>
      </select>
    </div>
  </div>

  <div class="admin-camp">
    <label for="rezumat">Rezumat</label>
    <textarea id="rezumat" name="rezumat" rows="2" style="min-height:auto"><?= e($a['rezumat'] ?? '') ?></textarea>
    <span class="ajutor">Apare în listă și în previzualizarea de pe rețele. Dacă îl lași gol, îl generez din text.</span>
  </div>

  <!-- Imaginea de copertă -->
  <div class="admin-camp">
    <label>Imagine de copertă</label>
    <div class="incarca" data-incarca data-tinta="imagine" tabindex="0" role="button">
      <span data-incarca-text>Trage o imagine aici sau apasă ca să alegi. Se redimensionează și devine WebP automat.</span>
      <div class="incarca__progres" hidden><div class="incarca__bara"></div></div>
    </div>
    <input type="hidden" name="imagine" id="imagine" value="<?= e($a['imagine'] ?? '') ?>">
    <div id="imagine-previzualizare" style="margin-top:var(--s2)">
      <?php if (!empty($a['imagine'])): ?>
        <img src="<?= e(url('uploads/' . $a['imagine'])) ?>" alt="" style="max-width:16rem;border-radius:var(--raza-mediu)">
      <?php endif ?>
    </div>
  </div>

  <div class="admin-camp">
    <label for="imagine_alt">Text alternativ pentru copertă</label>
    <input type="text" id="imagine_alt" name="imagine_alt" value="<?= e($a['imagine_alt'] ?? '') ?>">
    <span class="ajutor">Descrie imaginea pentru cine nu o vede. Lasă gol dacă e pur decorativă.</span>
  </div>

  <!-- Corpul articolului -->
  <div class="admin-camp">
    <label for="continut">Conținut</label>
    <div data-editor
         data-tinta="continut"
         data-incarca-url="<?= e(url('admin/incarca-imagine')) ?>"
         data-csrf="<?= e(Auth::tokenCsrf()) ?>"></div>
    <textarea id="continut" name="continut" rows="18"><?= e($a['continut'] ?? '') ?></textarea>
    <span class="editor-stare" data-editor-stare aria-live="polite"></span>
  </div>

  <div class="admin-camp">
    <label for="meta_descriere">Meta descriere (SEO)</label>
    <input type="text" id="meta_descriere" name="meta_descriere" maxlength="180"
           value="<?= e($a['meta_descriere'] ?? '') ?>">
    <span class="ajutor">Textul din rezultatele Google. Maximum 180 de caractere. Gol = generat din rezumat.</span>
  </div>

  <div class="admin-actiuni">
    <button type="submit" name="actiune" value="ciorna" class="buton buton--secundar">Salvează ciorna</button>
    <button type="submit" name="actiune" value="publica" class="buton buton--principal">Publică</button>

    <?php if (!$esteNou): ?>
      <span class="spatiu"></span>
      <button type="submit" formaction="<?= e(url('admin/articole/sterge')) ?>" class="buton--sterge"
              data-confirma="Ștergi articolul „<?= e($a['titlu']) ?>”? Nu se mai poate recupera.">
        Șterge articolul
      </button>
    <?php endif ?>
  </div>
</form>
