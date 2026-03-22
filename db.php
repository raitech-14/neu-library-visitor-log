<?php
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

try {
    // Ensure there are no spaces or typos inside the string
    $dsn = "pgsql:host=$host;port=$port;dbname=$db"; 
    
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Remove the echo "Connected" once it works to avoid issues with header redirects
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
