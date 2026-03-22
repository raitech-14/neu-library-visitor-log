<?php
session_start();
require_once 'google-config.php';
include 'db.php'; // PDO connection

// Step 1: Get the authorization code from Google
if (!isset($_GET['code'])) {
    header("Location: admin_login.php");
    exit();
}

$code = $_GET['code'];

// Step 2: Exchange code for access token
$token = $client->fetchAccessTokenWithAuthCode($code);

// Step 3: Check for errors
if (isset($token['error'])) {
    die("Error authenticating with Google: " . htmlspecialchars($token['error']));
}

// Step 4: Set the access token
$client->setAccessToken($token['access_token']);

// Step 5: Get user info
$google_service = new Google_Service_Oauth2($client);
$google_user = $google_service->userinfo->get();
$email = $google_user->email;

// Step 6: Check if this email exists in your users table as admin using PDO
$sql = "SELECT * FROM users WHERE email = :email AND role = 'admin'";
$stmt = $pdo->prepare($sql);
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Admin exists, log them in
    $_SESSION['admin'] = $email;
    header("Location: dashboard.php");
    exit();
} else {
    die("This Google account is not authorized as admin.");
}
?>
