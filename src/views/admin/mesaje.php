<?php /** Mesaje de contact, decriptate. Variabile: $mesaje */ ?>
<div class="admin-titlu-rand">
  <h1>Mesaje</h1>
</div>
<p class="meta" style="max-width:52ch; margin-bottom:var(--s4)">
  Mesajele din formularul de contact, decriptate doar aici. Ștergerea le ține
  30 de zile în caz de greșeală, apoi dispar definitiv.
</p>

<?php if (empty($mesaje)): ?>
  <p>Niciun mesaj deocamdată.</p>
<?php else: ?>
  <div class="stiva--larg">
    <?php foreach ($mesaje as $m): ?>
      <article style="background:var(--alb); border:1px solid var(--n-300); border-radius:var(--raza-mediu); padding:var(--s3);<?= $m['citit'] ? '' : ' border-left:3px solid var(--teracota);' ?>">
        <div style="display:flex; justify-content:space-between; gap:var(--s3); flex-wrap:wrap; align-items:baseline">
          <strong style="font-size:var(--t-corp)"><?= e($m['nume']) ?></strong>
          <span class="meta"><?= e(data_ro($m['primit_la'])) ?> · <?= e(date('H:i', strtotime($m['primit_la']))) ?></span>
        </div>

        <p class="meta" style="margin:var(--s1) 0">
          <strong>Contact:</strong> <?= e($m['contact']) ?><br>
          <strong>Unde e acum:</strong> <?= e($m['situatie_text']) ?>
        </p>

        <?php if (!empty($m['mesaj'])): ?>
          <p style="white-space:pre-line; margin:var(--s2) 0"><?= e($m['mesaj']) ?></p>
        <?php else: ?>
          <p class="meta" style="margin:var(--s2) 0">(fără mesaj)</p>
        <?php endif ?>

        <div class="admin-actiuni" style="border-top:0; padding-top:var(--s1); margin-top:0">
          <?php
            // Raspunde direct: mailto daca e email, tel daca pare telefon.
            $esteEmail = str_contains($m['contact'], '@');
            $href = $esteEmail ? 'mailto:' . $m['contact'] : 'tel:' . preg_replace('/\s+/', '', $m['contact']);
          ?>
          <a class="buton buton--secundar" href="<?= e($href) ?>" style="padding:0.4rem var(--s2); font-size:var(--t-mic)">
            <?= $esteEmail ? 'Răspunde prin email' : 'Sună' ?>
          </a>
          <form method="post" action="<?= e(url('admin/mesaje/sterge')) ?>" class="spatiu">
            <?= Auth::campCsrf() ?>
            <input type="hidden" name="id" value="<?= (int) $m['id'] ?>">
            <button type="submit" class="buton--sterge" data-confirma="Ștergi acest mesaj?">Șterge</button>
          </form>
        </div>
      </article>
    <?php endforeach ?>
  </div>
<?php endif ?>
