<<<<<<< HEAD
<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "neu_library_log";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
=======
<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "neu_library_log";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
>>>>>>> 3dd07543472475057b3aa40fa4d6caf9c87cc434
?>