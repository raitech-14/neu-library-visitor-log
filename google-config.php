<<<<<<< HEAD
<?php
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig('credentials.json'); // iyong na-save mong JSON
$client->addScope("email");
$client->addScope("profile");
$client->setRedirectUri('http://localhost/VisitorLoginSystem/google-callback.php');
?>
=======
<?php
require_once 'vendor/autoload.php';

$client = new Google_Client();

$client->setClientId(getenv('GOOGLE_CLIENT_ID'));
$client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
$client->setRedirectUri(getenv('GOOGLE_REDIRECT_URI'));

$client->addScope("email");
$client->addScope("profile");
>>>>>>> 3dd07543472475057b3aa40fa4d6caf9c87cc434
