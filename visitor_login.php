<?php
session_start();
date_default_timezone_set('Asia/Manila');
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $college = trim($_POST['college']);
    $visitor_type = trim($_POST['visitor_type']);
    $purpose = trim($_POST['purpose']);

    if (!str_ends_with($email, "@neu.edu.ph")) {
        $_SESSION['message'] = "Only valid institutional email is allowed.";
        header("Location: visitor_login.php");
        exit();
    }

    if (($visitor_type === "Student" || $visitor_type === "Professor") && empty($college)) {
        $_SESSION['message'] = "College / Department is required for Students and Professors.";
        header("Location: visitor_login.php");
        exit();
    }

    if (($visitor_type === "Staff" || $visitor_type === "Faculty") && empty($college)) {
        $college = NULL;
    }

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($user['is_blocked'] == 1) {
            $_SESSION['blocked_name'] = $user['full_name'];
            header("Location: blocked.php");
            exit();
        }

        $update_sql = "UPDATE users SET full_name = ?, college = ?, visitor_type = ? WHERE email = ?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("ssss", $full_name, $college, $visitor_type, $email);
        $stmt_update->execute();

        $user_id = $user['id'];
    } else {
        $role = "visitor";
        $insert_user = "INSERT INTO users (email, full_name, college, role, visitor_type) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($insert_user);
        $stmt_insert->bind_param("sssss", $email, $full_name, $college, $role, $visitor_type);
        $stmt_insert->execute();

        $user_id = $conn->insert_id;
    }

    $visit_date = date("Y-m-d");
    $visit_time = date("H:i:s");

    $insert_log = "INSERT INTO visit_logs (user_id, purpose, visit_date, visit_time) VALUES (?, ?, ?, ?)";
    $stmt_log = $conn->prepare($insert_log);
    $stmt_log->bind_param("isss", $user_id, $purpose, $visit_date, $visit_time);
    $stmt_log->execute();

    $_SESSION['visitor_name'] = $full_name;
    $_SESSION['purpose'] = $purpose;

    header("Location: checkin_success.php");
    exit();
}

$message = "";
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>
