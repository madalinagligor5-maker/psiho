<?php
/** Layout minimal — doar pentru pagina de autentificare. Fara bara laterala. */
$titlu = $titlu ?? 'Autentificare';
?>
<!doctype html>
<html lang="ro">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($titlu) ?> — Administrare</title>
<link rel="stylesheet" href="<?= e(asset('assets/css/site.css')) ?>">
<link rel="stylesheet" href="<?= e(asset('assets/css/admin.css')) ?>">
</head>
<body class="admin">
  <div class="login-cadru">
    <?= $continut ?>
  </div>
</body>
</html>
