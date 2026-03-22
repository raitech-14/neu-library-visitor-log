<?php
// db.php

$host     = 'db.qwhuiudqwvknzrzfawca.supabase.co';
$port     = '5432'; // Or 6543 for Connection Pooling
$dbname   = 'postgres';
$user     = 'postgres';
$password = 'raizavisaya.28'; // Replace this with your actual Supabase password

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $password);
    
    // Set error mode to Exception so you can catch errors easily
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optional: Set default fetch mode to Associative Array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // If the driver is STILL not found, this will tell us why
    die("Database connection failed: " . $e->getMessage());
}
