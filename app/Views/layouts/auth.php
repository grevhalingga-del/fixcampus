<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'FixCampus') ?> - FixCampus</title>
  <link rel="stylesheet" href="<?= e(asset('css/bootstrap.min.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
</head>
<body class="auth-page">
  <main class="auth-wrap">
    <?php require __DIR__ . '/../partials/flash.php'; ?>
    <?= $content ?>
  </main>
</body>
</html>
