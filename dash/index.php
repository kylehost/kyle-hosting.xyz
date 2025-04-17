<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '.kyle-hosting.xyz',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: https://kyle-hosting.xyz/auth/local/login.php");
    exit;
}

echo "Welcome, " . htmlspecialchars($_SESSION['username']) . "!";
?>