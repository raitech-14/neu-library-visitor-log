<?php
// db.php - connection to Supabase (PostgreSQL)

// Get credentials from environment variables
$host   = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user   = getenv('DB_USER');
$pass   = getenv('DB_PASS');
$port   = getenv('DB_PORT') ?: 5432; // default port if not set

try {
    // Connect using PDO
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; // optional for testing
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}