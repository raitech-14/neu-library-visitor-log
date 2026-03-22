<?php
// db.php

$host     = 'aws-1-ap-northeast-2.pooler.supabase.com'; 
$port     = '6543';                                   
$dbname   = 'postgres';
$user     = 'postgres.qwhuiudqwvknzrzfawca';          
$password = 'raizavisaya.28';                         

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";

try {
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES   => true, // Necessary for Transaction Pooler
    ];

    $pdo = new PDO($dsn, $user, $password, $options);
    
    echo "Success! Connection established via IPv4 Pooler.";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
