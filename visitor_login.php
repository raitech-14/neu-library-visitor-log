<?php
session_start();
date_default_timezone_set('Asia/Manila');
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $college = trim($_POST['college']);
    $purpose = trim($_POST['purpose']);

    if (!str_ends_with($email, "@neu.edu.ph")) {
        $_SESSION['message'] = "Only valid institutional email is allowed.";
        header("Location: visitor_login.php");
        exit();
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

        $update_sql = "UPDATE users SET full_name = ?, college = ? WHERE email = ?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("sss", $full_name, $college, $email);
        $stmt_update->execute();

        $user_id = $user['id'];
    } else {
        $role = "visitor";
        $insert_user = "INSERT INTO users (email, full_name, college, role) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($insert_user);
        $stmt_insert->bind_param("ssss", $email, $full_name, $college, $role);
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Check-In | NEU Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(180deg, #0b1957 0%, #132a73 100%);
            color: white;
        }

        .topbar {
            width: 100%;
            height: 64px;
            background: #08164d;
            display: flex;
            align-items: center;
            padding: 0 42px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }

        .brand {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }

        .page-wrap {
            min-height: calc(100vh - 64px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 34px 30px 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.18);
            backdrop-filter: blur(3px);
        }

        .login-title {
            font-size: 33px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
            color: #ffffff;
        }

        .login-subtitle {
            text-align: center;
            font-size: 15px;
            line-height: 1.5;
            color: #dbe4ff;
            margin-bottom: 22px;
        }

        .form-control,
        .form-select {
            height: 44px;
            border: none;
            border-radius: 8px;
            background: #f3f4f6;
            color: #111827;
            padding: 10px 14px;
            font-size: 15px;
            box-shadow: none !important;
        }

        .form-control:focus,
        .form-select:focus {
            border: none;
            outline: none;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.22) !important;
        }

        .btn-login {
            width: 100%;
            height: 44px;
            border: none;
            border-radius: 8px;
            background: #4d8df7;
            color: #fff;
            font-size: 17px;
            font-weight: 500;
            transition: 0.2s ease;
        }

        .btn-login:hover {
            background: #3f7ae0;
        }

        .alert {
            border-radius: 8px;
            font-size: 14px;
            padding: 10px 12px;
        }

        .bottom-link {
            margin-top: 16px;
            text-align: center;
            font-size: 14px;
            color: #dbe4ff;
        }

        .bottom-link a {
            color: #ffffff;
            font-weight: 600;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .topbar {
                padding: 0 20px;
            }

            .brand {
                font-size: 22px;
            }

            .login-card {
                padding: 28px 20px 24px;
            }

            .login-title {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>

    <div class="topbar">
        <h1 class="brand">NEU Library</h1>
    </div>

    <div class="page-wrap">
        <div class="login-card">
            <div class="login-title">Visitor Check-In</div>
            <div class="login-subtitle">
                Enter your details to record your library visit.
            </div>

            <?php if ($message != ""): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                </div>

                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Institutional Email" required>
                </div>

                <div class="mb-3">
                    <input type="text" name="college" class="form-control" placeholder="College" required>
                </div>

                <div class="mb-3">
                    <select name="purpose" class="form-select" required>
                        <option value="">Select Purpose of Visit</option>
                        <option value="Reading">Reading</option>
                        <option value="Researching">Researching</option>
                        <option value="Studying">Studying</option>
                        <option value="Use of Computer">Use of Computer</option>
                        <option value="Others">Others</option>
                    </select>
                </div>

                <button type="submit" class="btn-login">Check In</button>
            </form>

            <div class="bottom-link">
                Back to home? <a href="index.php">Click here</a>
            </div>
        </div>
    </div>

</body>
</html>