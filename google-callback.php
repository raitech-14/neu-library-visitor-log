<?php
session_start();
require_once 'google-config.php';
include 'db.php'; // This provides the $pdo object

// Step 1: Get the authorization code from Google
if (!isset($_GET['code'])) {
    header("Location: admin_login.php");
    exit();
}

$code = $_GET['code'];

try {
    // Step 2: Exchange code for access token
    $token = $client->fetchAccessTokenWithAuthCode($code);

    // Step 3: Check for errors in token exchange
    if (isset($token['error'])) {
        die("Error authenticating with Google: " . htmlspecialchars($token['error']));
    }

    // Step 4: Set the access token
    $client->setAccessToken($token['access_token']);

    // Step 5: Get user info
    $google_service = new Google_Service_Oauth2($client);
    $google_user = $google_service->userinfo->get();
    $email = $google_user->email;

    // Step 6: Check if this email exists in your users table as admin
    // We use $pdo (from db.php) and PDO named placeholders for security
    $sql = "SELECT * FROM users WHERE email = :email AND role = 'admin' LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        // Admin exists, log them in
        $_SESSION['admin'] = $email;
        header("Location: dashboard.php");
        exit();
    } else {
        // Admin email not found in database
        die("This Google account ($email) is not authorized as an admin.");
    }

} catch (Exception $e) {
    // Catch any Google API or PDO errors
    die("An error occurred: " . $e->getMessage());
}
?>
