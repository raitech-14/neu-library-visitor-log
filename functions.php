<?php
include_once 'db.php'; 

function logActivity($pdo, $activity, $admin_name = null) {
    try {
        $sql = "INSERT INTO activities (activity, admin_name, created_at) VALUES (?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$activity, $admin_name]);
    } catch (PDOException $e) {
        // This logs the error to your server logs instead of showing it to the user
        error_log("Activity log failed: " . $e->getMessage());
    }
}
