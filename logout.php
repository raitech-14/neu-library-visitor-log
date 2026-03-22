<?php
session_start();
include 'db.php';      
include 'functions.php'; 

if (isset($_SESSION['admin'])) {
    logActivity($pdo, "Admin logged out", $_SESSION['admin']);
}

$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
header("Location: index.php");
exit();
?>
