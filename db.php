<?php
$host     = 'aws-1-ap-northeast-2.pooler.supabase.com'; 
$port     = '6543'; 
$dbname   = 'postgres';
$user     = 'postgres.qwhuiudqwvknzrzfawca';
$password = 'raizavisaya.28';

// NOTICE THE SEMICOLONS (;) BELOW
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => true,
    ]);
    // If you get here, the DB is fixed!
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
