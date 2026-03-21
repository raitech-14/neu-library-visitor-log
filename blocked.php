<?php
session_start();
$name = $_SESSION['blocked_name'] ?? "Visitor";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden; 
            height: 100%;
            font-family: Arial, sans-serif;
            }

        body {
            background: url('assets/background.png') no-repeat center center/cover;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(139, 0, 0, 0.35); 
            backdrop-filter: blur(6px); 
            z-index: 0;
        }

        .blocked-container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .blocked-card {
            width: 100%;
            max-width: 380px;
            background: rgba(139, 0, 0, 0.9);
            border-radius: 16px;
            padding: 28px 24px;
            text-align: center;
            color: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.35);
            backdrop-filter: blur(6px);
        }

        .blocked-card h1 {
            font-size: 26px;
            margin-bottom: 10px;
        }

        .blocked-card p {
            font-size: 14px;
            line-height: 1.5;
            opacity: 0.95;
        }

        .blocked-card .btn {
            margin-top: 18px;
            display: inline-block;
            padding: 10px 22px;
            background: #ffffff;
            color: #8b0000;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.2s;
        }

        .blocked-card .btn:hover {
            background: #f1f1f1;
        }
    </style>
</head>
<body>

<div class="blocked-container">
    <div class="blocked-card">

        <h1>Access Denied</h1>

        <p>
            Sorry <b><?php echo htmlspecialchars($name); ?></b><br>
            You are currently <b>blocked</b> from entering the NEU Library.
        </p>

        <p>Please contact the library administrator.</p>

        <a class="btn" href="visitor_login.php">Back</a>

    </div>
</div>

</body>
</html>