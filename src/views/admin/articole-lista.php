<?php /** Lista de articole. Variabile: $articole */ ?>
<div class="admin-titlu-rand">
  <h1>Articole</h1>
  <a class="buton buton--principal" href="<?= e(url('admin/articole/nou')) ?>">Articol nou</a>
</div>

<?php if (empty($articole)): ?>
  <p>Încă niciun articol. <a href="<?= e(url('admin/articole/nou')) ?>">Scrie primul →</a></p>
<?php else: ?>
  <table class="admin-tabel">
    <thead>
      <tr><th>Titlu</th><th>Autor</th><th>Categorie</th><th>Stare</th><th>Dată</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($articole as $a): ?>
        <tr>
          <td><a href="<?= e(url('admin/articole/' . $a['id'])) ?>"><?= e($a['titlu']) ?></a></td>
          <td class="meta"><?= e($a['autor_nume'] ?? '—') ?></td>
          <td class="meta"><?= e($a['categorie_nume'] ?? '—') ?></td>
          <td>
            <span class="stare stare--<?= $a['stare'] === 'publicat' ? 'publicat' : 'ciorna' ?>">
              <?= $a['stare'] === 'publicat' ? 'Publicat' : 'Ciornă' ?>
            </span>
          </td>
          <td class="meta"><?= e(data_ro($a['publicat_la'] ?? $a['creat_la'])) ?></td>
          <td style="text-align:right">
            <?php if ($a['stare'] === 'publicat'): ?>
              <a href="<?= e(url('articol/' . $a['slug'])) ?>" target="_blank" rel="noopener" class="meta">Vezi ↗</a>
            <?php else: ?>
              <a href="<?= e(url('admin/previzualizare/' . $a['id'])) ?>" target="_blank" rel="noopener" class="meta">Previzualizează ↗</a>
            <?php endif ?>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php endif ?>
