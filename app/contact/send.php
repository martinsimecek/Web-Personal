<?php
declare(strict_types=1);

session_start();

// POST only
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
  http_response_code(405);
  exit;
}

// CSRF check
$csrfPost = $_POST['csrf'] ?? '';
$csrfSess = $_SESSION['csrf'] ?? '';
if ($csrfPost === '' || $csrfSess === '' || !hash_equals($csrfSess, $csrfPost)) {
  header('Location: /index.php?sent=0');
  exit;
}

// Honeypot
if (!empty($_POST['homepage'] ?? '')) {
  header('Location: /index.php?sent=1');
  exit;
}

// Language routing
$supported = ['cs', 'en', 'de'];
$lang = $_POST['lang'] ?? 'cs';
if (!in_array($lang, $supported, true)) {
  $lang = 'cs';
}

// Sanitization
$clean = function (string $s): string {
  $s = trim($s);
  return preg_replace('/[\x00-\x1F\x7F]/u', '', $s) ?? '';
};

$name  = $clean((string)($_POST['name'] ?? ''));
$email = filter_var((string)($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL) ?: '';
$msg   = $clean((string)($_POST['message'] ?? ''));

// Required fields
if ($name === '' || $email === '' || $msg === '') {
  header("Location: /index.php?lang={$lang}&sent=0");
  exit;
}

// Load mail config
$configFile = __DIR__ . '/../config/mail.php';
if (!is_readable($configFile)) {
  http_response_code(500);
  exit;
}

$mailCfg = require $configFile;

// Mandatory config values
if (empty($mailCfg['to']) || empty($mailCfg['from'])) {
  http_response_code(500);
  exit;
}

$to = $mailCfg['to'];
$from = $mailCfg['from'];

// Email content
$subject = 'New message from contact form';
$body = "Name: {$name}\n"
      . "Email: {$email}\n\n"
      . "Message:\n{$msg}\n";

$headers = [
  'Content-Type: text/plain; charset=UTF-8',
  'From: ' . $from,
  'Reply-To: ' . $email,
];

// Send mail
$ok = @mail($to, $subject, $body, implode("\r\n", $headers));

// Redirect
$flag = $ok ? '1' : '0';
header("Location: /index.php?lang={$lang}&sent={$flag}");
exit;
?>