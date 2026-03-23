PHP
<?php
// functions.php
include_once 'db.php'; 

function logActivity($pdo, $activity, $admin_name = null) {
    if (!$pdo) {
        error_log("Activity log failed: No PDO connection available.");
        return;
    }

    try {
        $sql = "INSERT INTO activities (activity, admin_name, created_at) VALUES (?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$activity, $admin_name]);
    } catch (PDOException $e) {
        error_log("Activity log failed: " . $e->getMessage());
    }
}

