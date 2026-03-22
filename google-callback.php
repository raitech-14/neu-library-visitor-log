<?php
session_start();
require_once 'google-config.php';
include 'db.php';

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

// TEMPORARY: bypass DB check
$_SESSION['admin'] = $email;
header("Location: dashboard.php");
exit();
