<?php

require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_PORT', $_ENV['DB_PORT']);
define('BASE_URL', $_ENV['BASE_URL']);
define('SITE_NAME', $_ENV['SITE_NAME']);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>