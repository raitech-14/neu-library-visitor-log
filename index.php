<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEU Library Visitor Log</title>
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
    </style>
</head>
<body>

    <div class="topbar">
        <h1 class="brand">NEU Library</h1>
    </div>

    <div class="page-wrap">
        <div class="main-card">
            <div class="main-title">NEU Library Visitor Log</div>
            <div class="main-subtitle">
                 Please select your login type to continue.
            </div>

            <a href="visitor_login.php" class="btn-main">Visitor Login</a>
            <a href="admin_login.php" class="btn-outline-custom">Admin Login</a>
        </div>
    </div>

</body>
</html>