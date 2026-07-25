<?php /** Pagina de autentificare. Variabile: $eroare */ ?>
<div class="login-card">
  <h1>Bine ai revenit</h1>
  <p class="meta">Intră în panoul de administrare.</p>

  <?php if (!empty($eroare)): ?>
    <div class="flash flash--eroare" role="alert" style="animation:none">
      <span class="flash__semn" aria-hidden="true">!</span>
      <span><?= e($eroare) ?></span>
    </div>
  <?php endif ?>

  <form method="post" action="<?= e(url('admin/autentificare')) ?>" class="admin-form">
    <?= Auth::campCsrf() ?>

    <div class="admin-camp">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required autocomplete="username" autofocus>
    </div>

    <div class="admin-camp">
      <label for="parola">Parolă</label>
      <input type="password" id="parola" name="parola" required autocomplete="current-password">
    </div>

    <button type="submit" class="buton buton--principal" style="width:100%">Intră</button>
  </form>
</div>
