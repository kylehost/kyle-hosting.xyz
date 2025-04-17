<?php
session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: https://kyle-hosting.xyz/auth/local/login.php");
    exit;
}

echo "Welcome, " . htmlspecialchars($_SESSION['username']) . "!";
?>