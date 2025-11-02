<?php
declare(strict_types=1);

// --- Konfigurace jazyků ---
$root    = dirname(__DIR__);
$langDir = $root . '/app/lang';
$supported = ['cs','en','de'];

$lang = $_GET['lang'] ?? 'cs';
if (!in_array($lang, $supported, true)) $lang = 'cs';

// --- Načtení překladů ---
$T = require $langDir . "/$lang.php";
$e = fn(string $s) => htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$t = fn(string $k) => $T[$k] ?? $k;

// --- Session pro CSRF token ---
session_start();
$_SESSION['csrf'] ??= bin2hex(random_bytes(16));
$csrf = $_SESSION['csrf'];

// --- Flash zprávy z odeslání formuláře ---
$sent  = isset($_GET['sent']) && $_GET['sent'] === '1';
$error = isset($_GET['sent']) && $_GET['sent'] === '0';
?>
<!DOCTYPE html>
<html lang="<?= $e($lang) ?>" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $e($t('meta.description')) ?>">
    <meta name="author" content="<?= $e($t('meta.author')) ?>">

    <title><?= $e($t('meta.title')) ?></title>

    <link rel="canonical" href="<?= $e($t('meta.canonical')) ?>/">

    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/cover.css">
    <link rel="stylesheet" href="/assets/css/custom.css">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/assets/img/favicons/safari-pinned-tab.svg" color="#712cf9">
    <link rel="icon" href="/assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#ffffff">
  </head>

  <body class="d-flex h-100 text-center text-bg-dark">

    <!-- Container -->
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">

      <!-- Header -->
      <header class="mb-auto">
        <div>
          <h3 class="float-md-start mb-0"><?= $e($t('header.title')) ?></h3>
          <nav class="nav nav-masthead justify-content-center float-md-end">
            <a class="nav-link fw-bold py-1 px-0" href="<?= $e($t('header.link1url')) ?>"><?= $e($t('header.link1')) ?></a>
            <a class="nav-link fw-bold py-1 px-0" href="<?= $e($t('header.link2url')) ?>"><?= $e($t('header.link2')) ?></a>
            <a class="nav-link fw-bold py-1 px-0" href="<?= $e($t('header.link3url')) ?>"><?= $e($t('header.link3')) ?></a>
          </nav>
        </div>
      </header>
      <!-- Header End -->

      <!-- Main -->
      <main class="px-3">
        <h1><?= $e($t('main.title')) ?></h1>
        <p class="lead"><?= $e($t('main.description')) ?></p>
        <p class="lead">
          <a href="<?= $e($t('main.buttonurl')) ?>" class="btn btn-lg btn-light fw-bold border-white bg-white"><?= $e($t('main.button')) ?></a>
        </p>
      </main>
      <!-- Main End -->

      <!-- Footer -->
      <footer class="mt-auto text-white-50">
        <p>
          <?= $e($t('footer.text')) ?>
          <br>
          <a href="<?= $e($t('footer.linkurl')) ?>" class="text-white"><?= $e($t('footer.link')) ?></a>
        </p>
      </footer>
      <!-- Footer End -->

    </div>
    <!-- Container End -->

    <!-- JS -->
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <!-- JS End -->

  </body>
</html>