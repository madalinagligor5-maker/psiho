<?php
/** Tablou de bord. Variabile: $nr_articole, $nr_ciorne, $nr_mesaje_noi, $ultimele */
?>
<div class="admin-titlu-rand">
  <h1>Tablou de bord</h1>
  <a class="buton buton--principal" href="<?= e(url('admin/articole/nou')) ?>">Articol nou</a>
</div>

<div class="admin-sumar">
  <div class="sumar-card">
    <div class="sumar-card__nr"><?= (int) $nr_articole ?></div>
    <p class="sumar-card__et"><a href="<?= e(url('admin/articole')) ?>">Articole în total</a></p>
  </div>
  <div class="sumar-card">
    <div class="sumar-card__nr"><?= (int) $nr_ciorne ?></div>
    <p class="sumar-card__et">Ciorne nepublicate</p>
  </div>
  <div class="sumar-card">
    <div class="sumar-card__nr"><?= (int) $nr_mesaje_noi ?></div>
    <p class="sumar-card__et"><a href="<?= e(url('admin/mesaje')) ?>">Mesaje necitite</a></p>
  </div>
</div>

<div class="admin-titlu-rand">
  <h2>Articole recente</h2>
</div>

<?php if (empty($ultimele)): ?>
  <p>Încă niciun articol. <a href="<?= e(url('admin/articole/nou')) ?>">Scrie primul →</a></p>
<?php else: ?>
  <table class="admin-tabel">
    <thead>
      <tr><th>Titlu</th><th>Stare</th><th>Dată</th></tr>
    </thead>
    <tbody>
      <?php foreach (array_slice($ultimele, 0, 6) as $a): ?>
        <tr>
          <td><a href="<?= e(url('admin/articole/' . $a['id'])) ?>"><?= e($a['titlu']) ?></a></td>
          <td>
            <span class="stare stare--<?= $a['stare'] === 'publicat' ? 'publicat' : 'ciorna' ?>">
              <?= $a['stare'] === 'publicat' ? 'Publicat' : 'Ciornă' ?>
            </span>
          </td>
          <td class="meta"><?= e(data_ro($a['publicat_la'] ?? $a['creat_la'])) ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php endif ?>

<div class="admin-actiuni" style="border-top:0; margin-top:var(--s5)">
  <div>
    <h2 style="font-size:var(--t-h3)">Export de siguranță</h2>
    <p class="meta" style="max-width:42ch">
      Scrie fiecare articol ca fișier Markdown în <code>content-export/</code>.
      Se face automat la fiecare publicare; butonul reface tot, manual.
    </p>
  </div>
  <form method="post" action="<?= e(url('admin/exporta')) ?>" class="spatiu">
    <?= Auth::campCsrf() ?>
    <button type="submit" class="buton buton--secundar">Exportă tot acum</button>
  </form>
</div>
