<?php
session_start();

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '.kyle-hosting.xyz',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);


$host = 'gateway01.us-west-2.prod.aws.tidbcloud.com';
$port = '4000';
$dbname = 'login';
$username = '3dgdBU9CZfF4dxT.root';
$password = 'Re2X8ioosdIdH4vV';

$ca = './ca.pem';

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

try {
    $options = [
        PDO::MYSQL_ATTR_SSL_CA => $ca,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT DATABASE()");
    $currentDatabase = $stmt->fetchColumn();
    
    if ($currentDatabase) {
        echo "Connected to database: " . $currentDatabase;
    } else {
        echo "No database selected.";
    }
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>