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
    <link rel="stylesheet" href="/assets/css/main.css">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/assets/img/favicons/safari-pinned-tab.svg" color="#712cf9">
    <link rel="icon" href="/assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#ffffff">
  </head>

  <body class="d-flex h-100 text-bg-dark">

    <!-- Container -->
    <div class="cover-container text-center d-flex w-100 h-100 p-3 mx-auto flex-column">

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

      <!-- Alert -->
<?php if ($sent): ?>
      <div class="alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
        <strong><?= $e($t('alert.success')) ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
<?php elseif ($error): ?>
      <div class="alert alert-danger alert-dismissible fade show text-center mt-3" role="alert">
        <strong><?= $e($t('alert.error')) ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
<?php endif; ?>

      <!-- Main -->
      <main class="px-3">
        <img src="/assets/img/author.jpg" class="rounded-circle mx-auto d-block my-4" height="250" alt="Portrait of Author">
        <h1><?= $e($t('main.title')) ?></h1>
        <p class="lead d-none d-sm-block"><?= $e($t('main.description')) ?></p>
        <p>
          <a data-bs-toggle="modal" data-bs-target="#contactModal" class="btn btn-lg btn-light fw-bold"><?= $e($t('main.button1')) ?></a>
          <a class="btn btn-lg btn-outline-light fw-bold disabled" aria-disabled="true"><?= $e($t('main.button2')) ?></a>
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

    <!-- Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-dark text-white">
            <h1 class="modal-title fs-5" id="contactModalLabel"><?= $e($t('modal1.title')) ?></h1>
            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="/contact.php">
            <input type="hidden" name="csrf" value="<?= $e($csrf) ?>">
            <input type="hidden" name="lang" value="<?= $e($lang) ?>">
            <input type="hidden" name="homepage" autocomplete="off">
            <div class="modal-body">
              <div class="mb-3">
                <label for="inputName" class="form-label"><?= $e($t('modal1.name')) ?></label>
                <input type="text" class="form-control" id="inputName" name="name" placeholder="Joe Doe" required>
              </div>
              <div class="mb-3">
                <label for="inputEmail" class="form-label"><?= $e($t('modal1.email')) ?></label>
                <input type="email" class="form-control" id="inputEmail" name="email" placeholder="joedoe@example.com" required>
              </div>
              <div class="mb-3">
                <label for="inputMessage" class="form-label"><?= $e($t('modal1.message')) ?></label>
                <textarea class="form-control" id="inputMessage" name="message" rows="3" required></textarea>
              </div>
              <p><?= $e($t('modal1.text')) ?></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal"><?= $e($t('modal.close')) ?></button>
              <button type="submit" class="btn btn-dark"><?= $e($t('modal.send')) ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- JS -->
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <!-- JS End -->

  </body>
</html>