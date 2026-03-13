<?php
session_start();

if (!isset($_SESSION['visitor_name']) || !isset($_SESSION['purpose'])) {
    header("Location: visitor_login.php");
    exit();
}

$visitor_name = $_SESSION['visitor_name'];
$purpose = $_SESSION['purpose'];

unset($_SESSION['visitor_name']);
unset($_SESSION['purpose']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-In Successful | NEU Library</title>
    <meta http-equiv="refresh" content="4;url=visitor_login.php">
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

        .success-card {
            width: 100%;
            max-width: 520px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.18);
            backdrop-filter: blur(3px);
            text-align: center;
        }

        .check-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: rgba(77, 141, 247, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            font-weight: bold;
        }

        .success-title {
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .success-text {
            color: #dbe4ff;
            font-size: 17px;
            margin-bottom: 10px;
        }

        .success-name {
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .redirect-text {
            color: #bfd2ff;
            font-size: 14px;
            margin-top: 16px;
        }

        @media (max-width: 480px) {
            .topbar {
                padding: 0 20px;
            }

            .brand {
                font-size: 22px;
            }

            .success-title {
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
        <div class="success-card">
            <div class="check-icon">✓</div>
            <div class="success-title">Check-In Successful</div>
            <div class="success-text">Welcome to NEU Library,</div>
            <div class="success-name"><?php echo htmlspecialchars($visitor_name); ?></div>
            <div class="success-text">
                Your visit for <strong><?php echo htmlspecialchars($purpose); ?></strong> has been recorded.
            </div>
            <div class="redirect-text">
                Returning to check-in page in 4 seconds...
            </div>
        </div>
    </div>

</body>
</html>