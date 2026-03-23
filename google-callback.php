<?php
session_start();
require_once 'google-config.php';
include 'db.php'; 

function logActivity($pdo, $action, $user_email) {
    $sql = "INSERT INTO activity_logs (action, user_email, created_at) VALUES (:action, :user_email, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'action' => $action,
        'user_email' => $user_email
    ]);
}

if (!isset($_GET['code'])) {
    header("Location: admin_login.php");
    exit();
}

try {
    $code = $_GET['code'];
    $token = $client->fetchAccessTokenWithAuthCode($code);

    if (isset($token['error'])) {
        die("Error authenticating with Google: " . htmlspecialchars($token['error']));
    }

    $client->setAccessToken($token['access_token']);
    $google_service = new Google_Service_Oauth2($client);
    $google_user = $google_service->userinfo->get();
    $email = $google_user->email;

    // Step 6: This uses the $pdo we created in the new db.php
    $sql = "SELECT * FROM users WHERE email = :email AND role = 'admin'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['admin'] = $email;
        logActivity($pdo, "Admin logged in via Google", $email);
        header("Location: dashboard.php");
        exit();
    } else {
        die("This Google account ($email) is not authorized as admin.");
    }
} catch (Exception $e) {
    die("An error occurred: " . $e->getMessage());
}
?>
