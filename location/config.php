<?php
declare(strict_types=1);

/*
 * Local setup:
 * 1. Create a MySQL database named location_link_demo.
 * 2. Import database.sql in phpMyAdmin.
 * 3. Update DB credentials below if needed.
 * 4. Open http://localhost/location_link_demo/setup.php once, then delete setup.php.
 */
const DB_HOST = '127.0.0.1';
const DB_NAME = 'location_link_demo';
const DB_USER = 'root';
const DB_PASS = '';
const APP_NAME = 'Pinpoint';
const APP_BASE_URL = ''; // Optional, e.g. http://localhost/location_link_demo
const ADMIN_PASSWORD = 'ChangeThisPassword123!'; // Change before use beyond local testing.
const SESSION_NAME = 'pinpoint_admin';
date_default_timezone_set('Africa/Lagos');

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]
    );
    return $pdo;
}
function e(?string $v): string { return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function base_url(): string {
    if (APP_BASE_URL !== '') return rtrim(APP_BASE_URL, '/');
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    $dir = rtrim($dir, '/.');
    return $scheme . '://' . $host . ($dir ? $dir : '');
}
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_check(): void {
    $posted = (string)($_POST['csrf'] ?? '');
    if (!hash_equals((string)($_SESSION['csrf'] ?? ''), $posted)) {
        http_response_code(419); exit('Invalid request token. Refresh and try again.');
    }
}
function require_admin(): void {
    if (empty($_SESSION['admin_logged_in'])) {
        header('Location: login.php'); exit;
    }
}
function flash(string $type, string $message): void { $_SESSION['flash'] = ['type'=>$type, 'message'=>$message]; }
function get_flash(): ?array {
    $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return is_array($f) ? $f : null;
}
