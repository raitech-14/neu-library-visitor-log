<?php
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig('credentials.json'); 
$client->addScope("email");
$client->addScope("profile");
$client->setRedirectUri('http://localhost/VisitorLoginSystem/google-callback.php');
?>
