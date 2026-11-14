<?php
// Update these values after creating your MySQL database in your hosting control panel.

define('DB_HOST', 'YOUR_MYSQL_HOSTNAME');
define('DB_NAME', 'YOUR_DATABASE_NAME');
define('DB_USER', 'YOUR_MYSQL_USERNAME');
define('DB_PASS', 'YOUR_MYSQL_PASSWORD');


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }
    return $pdo;
}
function h($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function logged_in(): bool { return isset($_SESSION['user']); }
function require_login(): void {
    if (!logged_in()) { header('Location: login.php'); exit; }
}
function require_admin(): void {
    require_login();
    if ($_SESSION['user']['role'] !== 'admin') { http_response_code(403); exit('Access denied.'); }
}
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419); exit('Invalid form token. Please go back and try again.');
    }
}
function flash($message, $type='success'): void { $_SESSION['flash'] = [$message, $type]; }
function show_flash(): void {
    if (!empty($_SESSION['flash'])) {
        [$m,$t] = $_SESSION['flash']; unset($_SESSION['flash']);
        echo '<div class="alert '.h($t).'">'.h($m).'</div>';
    }
}
function complaint_code(): string { return 'SGM-' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(3))); }
