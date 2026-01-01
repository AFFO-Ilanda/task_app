<?php require_once __DIR__ . '/../includes/init.php'; ?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($page_title) ? e($page_title) : 'TaskApp' ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <link href="<?= e(url('public/assets/app.css')) ?>" rel="stylesheet">
</head>
<body>
  <?php if (is_logged_in()) include __DIR__ . '/navbar.php'; ?>

  <main class="container py-4">
