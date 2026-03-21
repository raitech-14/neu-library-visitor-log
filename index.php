<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <title>NEU Library Visitor Log</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        .center-content h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(180deg, #0b1957 0%, #132a73 100%);
            color: white;
        }

        .topbar {
            width: 100%;
            height: 70px; 
            background: #08164d;
            display: flex;
            align-items: center;
            padding: 0 42px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }

        .brand {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase; 
            margin: 0;
            color: #ffffff;
        }

        .page-wrap {
            min-height: calc(100vh - 64px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
        }

        .main-card {
            width: 100%;
            max-width: 430px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 34px 30px 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.18);
            backdrop-filter: blur(3px);
        }

        .main-title {
            font-size: 30px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 10px;
            color: #ffffff;
        }

        .main-subtitle {
            text-align: center;
            font-size: 15px;
            line-height: 1.5;
            color: #dbe4ff;
            margin-bottom: 24px;
        }

        .btn-main {
            width: 100%;
            height: 44px;
            border: none;
            border-radius: 8px;
            background: #4d8df7;
            color: #fff;
            font-size: 17px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: 0.2s ease;
            margin-bottom: 14px;
        }

        .btn-main:hover {
            background: #3f7ae0;
            color: #fff;
        }

        .btn-outline-custom {
            width: 100%;
            height: 44px;
            border-radius: 8px;
            border: 1px solid #7ea6ff;
            background: transparent;
            color: #ffffff;
            font-size: 17px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .btn-outline-custom:hover {
            background: rgba(255,255,255,0.08);
            color: #ffffff;
        }

        body {
            background: url('assets/background.png') no-repeat center center/cover;
        }

        @media (max-width: 480px) {
            .topbar {
                padding: 0 20px;
            }

            .brand {
                font-size: 22px;
            }

            .main-card {
                padding: 28px 20px 24px;
            }

            .main-title {
                font-size: 26px;
            }
        }
        
        .overlay {
            min-height: 100vh;
            background: rgba(0,0,0,0.55);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            width: 45px;
            height: 45px;
            object-fit: contain;
        }

        .center-content {
            text-align: center;
            margin-top: 120px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: #08164d;
            color: #fdfdfd;
            padding: 20px 40px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

    /* Add this inside your <style> tag */

    /* Fade-in animation */
    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Animate center content */
    .center-content {
        text-align: center;
        margin-top: 120px;
        animation: fadeInUp 1s ease forwards;
    }

    /* Animate cards with a stagger effect */
    .buttons .card {
        opacity: 0; /* start hidden */
        animation: fadeInUp 1s ease forwards;
    }

    .buttons .card:nth-child(1) {
        animation-delay: 0.3s;
    }

    .buttons .card:nth-child(2) {
        animation-delay: 0.6s;
    }

    </style>
</head>
<body>

<div class="overlay">

    <div class="topbar">
        <div class="topbar-left">
            <img src="assets/logo.png" class="logo">
            <h1 class="brand">New Era Library</h1>
        </div>
    </div>

    <div class="center-content">
        <h1>NEU Library Portal</h1>
        <p>Visitor Management System</p>

        <div class="buttons">
            <a href="visitor_login.php" class="card">Visitor Login</a>
            <a href="admin_login.php" class="card">Admin Login</a>
        </div>
    </div>

</div>

</body>
</html>
