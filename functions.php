PHP
<?php
// functions.php
include_once 'db.php'; 

function logActivity($pdo, $activity, $admin_name = null) {
    // Safety check: if $pdo isn't a valid connection, don't try to log
    if (!$pdo) {
        error_log("Activity log failed: No PDO connection available.");
        return;
    }

    try {
        // We use the column names from your screenshot: activity and admin_name
        $sql = "INSERT INTO activities (activity, admin_name, created_at) VALUES (?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$activity, $admin_name]);
    } catch (PDOException $e) {
        error_log("Activity log failed: " . $e->getMessage());
    }
}
// No closing ?> tag is actually better in a pure PHP file to prevent whitespace errors!
