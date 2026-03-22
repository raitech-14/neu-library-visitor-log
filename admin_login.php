<?php
session_start();
require_once 'google-config.php';
include 'db.php';
include 'functions.php';

$google_login_url = $client->createAuthUrl();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = md5($_POST['password']); 

    $sql = "SELECT * FROM users WHERE email = ? AND password = ? AND role = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['admin'] = $email;
        logActivity($pdo, "Admin logged in", $email);
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Invalid admin credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | NEU Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { 
            box-sizing: border-box; 
            font-family: Arial, sans-serif; 
        }

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

       .topbar {
            width: 100%;
            height: 70px;
            background: rgba(8, 22, 77, 0.95);
            display: flex;
            align-items: center;
            padding: 0 40px;
            position: relative;
            z-index: 2; 
        }

        .brand {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #ffffff;
            margin: 0;
        }

        .page-wrap {
            min-height: calc(100vh - 70px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
            position: relative;
            z-index: 1;
            max-width: 100%; 
        }

        .page-wrap {
            position: relative;
            z-index: 1;
        }
       
        .brand-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 34px 30px 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.18);
            backdrop-filter: blur(2px);
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

        .form-control {
            height: 44px;
            border: none;
            border-radius: 8px;
            background: #f3f4f6;
            color: #111827;
            padding: 10px 14px;
            font-size: 15px;
            box-shadow: none !important;
        }

        .form-control:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.22);
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

        .google-login-container {
            margin-top: 16px;
        }

        .google-login-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;          
            height: 44px;         
            border-radius: 8px;
            background-color: #4285F4;
            color: #fff;
            font-weight: 500;
            font-size: 17px;     
            text-decoration: none;
            transition: 0.2s ease;
        }

        .google-login-btn:hover {
            background-color: #3367d6;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 28px 20px;
            }

            .login-title {
                font-size: 28px;
            }

            .login-subtitle {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <div class="page-wrap">
    <div class="login-card">
        <div class="login-title">Admin Login</div>
        <div class="login-subtitle">
            Enter your admin credentials to access the dashboard.
        </div>

        <?php if ($message != ""): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- NORMAL LOGIN FORM -->
        <form method="POST">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Admin Email" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <!-- GOOGLE LOGIN (SEPARATE, NOT INSIDE FORM) -->
        <div class="google-login-container">
            <a href="<?php echo htmlspecialchars($google_login_url); ?>" class="google-login-btn">
                Login with Google 
            </a>
        </div>

        <div class="bottom-link">
            Back to home? <a href="index.php">Click here</a>
        </div>
    </div>
</div>

</body>
</html>
