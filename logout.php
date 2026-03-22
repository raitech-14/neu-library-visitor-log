<?php
session_start();
include 'functions.php'; 

if (isset($_SESSION['admin'])) {
    logActivity($pdo, "Admin logged out", $_SESSION['admin']);
}

session_destroy();
header("Location: index.php");
exit();
