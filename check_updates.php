<<<<<<< HEAD
<?php
include 'db.php';

$sql = "SELECT COUNT(*) as total FROM visit_logs";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo json_encode($row);
=======
<?php
include 'db.php';

$sql = "SELECT COUNT(*) as total FROM visit_logs";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo json_encode($row);
>>>>>>> 3dd07543472475057b3aa40fa4d6caf9c87cc434
?>