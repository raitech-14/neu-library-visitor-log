<?php
session_start();
date_default_timezone_set('Asia/Manila');
include 'db.php'; 
include 'functions.php';

// PDO to pg_connect for logActivity
$conn = pg_connect("host=aws-1-ap-northeast-2.pooler.supabase.com port=6543 dbname=postgres user=postgres.qwhuiudqwvknzrzfawca password=raizavisaya.28");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $college = trim($_POST['college']);
    $visitor_type = trim($_POST['visitor_type']);
    $purpose = trim($_POST['purpose']);

    // --- Validation Rules (Unchanged) ---
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

    // --- Database Operations (Updated for PDO) ---
    
    // 1. Check if user exists
    $sql = "SELECT id, full_name, is_blocked FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Check if blocked
        if ($user['is_blocked'] == 1) {
            logActivity($conn, "Attempted login by blocked user: " . $user['full_name']); 
            $_SESSION['blocked_name'] = $user['full_name'];
            header("Location: blocked.php");
            exit();
        }

        // Update existing user
        $update_sql = "UPDATE users SET full_name = ?, college = ?, visitor_type = ? WHERE email = ?";
        $pdo->prepare($update_sql)->execute([$full_name, $college, $visitor_type, $email]);
        $user_id = $user['id'];

        logActivity($conn, "Updated visitor info: " . $full_name . " (" . $email . ")"); 
    } else {
        
        $role = "visitor";
        $insert_user = "INSERT INTO users (email, full_name, college, role, visitor_type) VALUES (?, ?, ?, ?, ?) RETURNING id";
        $stmt_insert = $pdo->prepare($insert_user);
        $stmt_insert->execute([$email, $full_name, $college, $role, $visitor_type]);
        
        $new_user = $stmt_insert->fetch(PDO::FETCH_ASSOC);
        $user_id = $new_user['id'];
        logActivity($conn, "New visitor added: " . $full_name . " (" . $email . ")"); 
    }

    // 2. Record the visit
    $visit_date = date("Y-m-d");
    $visit_time = date("H:i:s");

    $insert_log = "INSERT INTO visit_logs (user_id, purpose, visit_date, visit_time) VALUES (?, ?, ?, ?)";
    $pdo->prepare($insert_log)->execute([$user_id, $purpose, $visit_date, $visit_time]);
    logActivity($conn, "Visitor checked in: " . $full_name . " for " . $purpose);

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Check-In | NEU Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }

        body {
            margin: 0;
            min-height: 100vh;
            background: url('background.png') no-repeat center center/cover;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(8, 22, 77, 0.55); 
            backdrop-filter: blur(2px);
            z-index: 0;
        }

        .page-wrap {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 50px; 
            padding: 40px;
        }

        .left-panel {
            width: 380px;
            color: white;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }

        .main-logo { width: 140px; margin-bottom: 20px; }
        .uni-name { font-size: 14px; letter-spacing: 3px; opacity: 0.85; }
        .library-text { font-size: 60px; font-weight: 800; margin: 5px 0; }
        .sub-text { font-size: 18px; color: #facc15; }

        .login-card {
            width: 100%;
            max-width: 360px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 28px 24px;
            backdrop-filter: blur(6px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .login-title { 
            font-size: 24px; 
            font-weight: 700; 
            text-align: center; 
            margin-bottom: 6px; 
            color: #fff; 
        }
        .login-subtitle { 
            text-align: center; 
            font-size: 13px; color: #cbd5ff; 
            margin-bottom: 16px; }

        .form-control, .form-select { 
            height: 40px; 
            border-radius: 6px; 
            font-size: 14px; 
            margin-bottom: 10px; 
            background: #f3f4f6; 
        }
        
        .btn-login { 
            width: 100%; 
            height: 40px; 
            border-radius: 6px; 
            font-size: 15px; 
            background: #4d8df7; 
            border: none; color: #fff; 
        }
        
        .btn-login:hover { 
            background: #3f7ae0; 
        }

        .alert { 
            font-size: 13px; 
            padding: 8px; 
            margin-bottom: 12px; 
        }
        
        .bottom-link { 
            margin-top: 12px; 
            text-align: center; 
            font-size: 13px; 
            color: #dbe4ff; 
        }
        
        .bottom-link a { 
            color: #ffffff; 
            font-weight: 600; 
            text-decoration: underline; 
        }

        @media (max-width: 768px) {
            .page-wrap { flex-direction: column; text-align: center; padding: 30px; }   
            .library-text { font-size: 40px; }
        }

        #datetime { margin-top: 10px; font-size: 20px; font-weight: 600; color: #fcfbf8; letter-spacing: 1px; }
    </style>
</head>

<body>
<div class="page-wrap">

    <div class="left-panel">
        <img src="logo.png" class="main-logo">
        <h2 class="uni-name">New Era University</h2>
        <h1 class="library-text">Library</h1>
        <p class="sub-text">Visitor Log System</p>
        <div id="datetime"></div>
    </div>

    <div class="login-card">
        <div class="login-title">Visitor Check-In</div>
        <div class="login-subtitle">Enter your details to record your visit</div>

        <?php if ($message != ""): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
            <input type="email" name="email" class="form-control" placeholder="Institutional Email" required>
            <input type="text" name="college" id="college" class="form-control" placeholder="College / Department">

            <select name="visitor_type" id="visitor_type" class="form-select" required>
                <option value="">Visitor Type</option>
                <option value="Student">Student</option>
                <option value="Faculty">Faculty</option>
                <option value="Staff">Staff</option>
                <option value="Professor">Professor</option>
            </select>

            <select name="purpose" id="purpose" class="form-select" required>
                <option value="">Purpose</option>
            </select>

            <button type="submit" class="btn-login">Check In</button>
        </form>

        <div class="bottom-link">
            Back to home? <a href="index.php">Click here</a>
        </div>
    </div>

</div>

<script>
const visitorType = document.getElementById("visitor_type");
const collegeField = document.getElementById("college");
const purpose = document.getElementById("purpose");

function updatePurpose() {
    let options = "";
    if (visitorType.value === "Student") {
        options = `
            <option value="">Purpose</option>
            <option>Studying</option>
            <option>Researching</option>
            <option>Group Study</option>
            <option>Quiet Space</option>
            <option>Borrowing Books</option>
            <option>Returning Books</option>
            <option>Printing</option>
        `;
    } else if (visitorType.value === "Faculty" || visitorType.value === "Staff") {
        options = `
            <option value="">Purpose</option>
            <option>Duty</option>
            <option>Office Work</option>
            <option>Meeting</option>
            <option>Library Task</option>
        `;
    } else if (visitorType.value === "Professor") {
        options = `
            <option value="">Purpose</option>
            <option>Lecture Preparation</option>
            <option>Research</option>
            <option>Meeting</option>
        `;
    } else {
        options = `
            <option value="">Purpose</option>
            <option>Inquiry</option>
            <option>Appointment</option>
            <option>Others</option>
        `;
    }
    purpose.innerHTML = options;
}

function updateCollegeRule() {
    if (visitorType.value === "Student" || visitorType.value === "Professor") {
        collegeField.required = true;
        collegeField.placeholder = "College / Department (Required)";
    } else {
        collegeField.required = false;
        collegeField.placeholder = "College / Department (Optional)";
    }
}

visitorType.addEventListener("change", () => { updatePurpose(); updateCollegeRule(); });
window.addEventListener("load", () => { updatePurpose(); updateCollegeRule(); });

function updateDateTime() {
    const now = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = now.toLocaleDateString('en-US', options);
    const time = now.toLocaleTimeString();
    document.getElementById("datetime").innerHTML = date + " | " + time;
}
setInterval(updateDateTime, 1000);
updateDateTime();
</script>
</body>
</html>
