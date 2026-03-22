<?php
$host = 'YOUR_HOST';
$port = '5432';
$db   = 'YOUR_DB';
$user = 'YOUR_USER';
$pass = 'YOUR_PASSWORD';

$conn = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass");
if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

function logActivity($conn, $activity, $admin_name = null) {
    $query = "INSERT INTO activities (activity, admin_name) VALUES ($1, $2)";
    $result = pg_query_params($conn, $query, array($activity, $admin_name));
    if (!$result) {
        error_log("Activity log failed: " . pg_last_error($conn));
    }
}
