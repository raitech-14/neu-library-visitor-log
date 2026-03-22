<?php
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

try {
    // This creates the $pdo object that your callback is looking for
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    // If this fails, it's likely because your getenv variables are empty
    die("Database connection failed: " . $e->getMessage());
}
?>
