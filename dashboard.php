<?php
session_start();
date_default_timezone_set('Asia/Manila');
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

/* BLOCK / UNBLOCK */
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    $user_id = (int) $_GET['user_id'];
    $action = $_GET['action'];

    if ($action === 'block') {
        $sql_block = "UPDATE users SET is_blocked = 1 WHERE id = ? AND role = 'visitor'";
        $stmt_block = $conn->prepare($sql_block);
        $stmt_block->bind_param("i", $user_id);
        $stmt_block->execute();
    }

    if ($action === 'unblock') {
        $sql_unblock = "UPDATE users SET is_blocked = 0 WHERE id = ? AND role = 'visitor'";
        $stmt_unblock = $conn->prepare($sql_unblock);
        $stmt_unblock->bind_param("i", $user_id);
        $stmt_unblock->execute();
    }

    header("Location: dashboard.php");
    exit();
}

/* COUNTS */
$today = date("Y-m-d");
$currentMonth = date("m");
$currentYear = date("Y");

$daily_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM visit_logs WHERE visit_date = ?");
$daily_stmt->bind_param("s", $today);
$daily_stmt->execute();
$daily_result = $daily_stmt->get_result();
$daily = $daily_result->fetch_assoc()['total'];

$weekly_query = $conn->query("SELECT COUNT(*) AS total FROM visit_logs WHERE YEARWEEK(visit_date, 1) = YEARWEEK(CURDATE(), 1)");
$weekly = $weekly_query->fetch_assoc()['total'];

$monthly_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM visit_logs WHERE MONTH(visit_date) = ? AND YEAR(visit_date) = ?");
$monthly_stmt->bind_param("ss", $currentMonth, $currentYear);
$monthly_stmt->execute();
$monthly_result = $monthly_stmt->get_result();
$monthly = $monthly_result->fetch_assoc()['total'];

$total_users_query = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'visitor'");
$total_users = $total_users_query->fetch_assoc()['total'];

/* FILTERS */
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_date = isset($_GET['filter_date']) ? trim($_GET['filter_date']) : '';

$sql = "SELECT visit_logs.*, users.full_name, users.email, users.college, users.is_blocked, users.id AS user_id
        FROM visit_logs
        INNER JOIN users ON visit_logs.user_id = users.id
        WHERE users.role = 'visitor'";

$params = [];
$types = "";

if ($search !== '') {
    $sql .= " AND (users.full_name LIKE ? OR users.email LIKE ? OR users.college LIKE ?)";
    $search_like = "%{$search}%";
    $params[] = $search_like;
    $params[] = $search_like;
    $params[] = $search_like;
    $types .= "sss";
}

if ($filter_date !== '') {
    $sql .= " AND visit_logs.visit_date = ?";
    $params[] = $filter_date;
    $types .= "s";
}

$sql .= " ORDER BY visit_logs.visit_date DESC, visit_logs.visit_time DESC, visit_logs.id DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

/* PER COLLEGE - based on actual check-ins */
$college_sql = "SELECT users.college, COUNT(visit_logs.id) AS total
                FROM visit_logs
                INNER JOIN users ON visit_logs.user_id = users.id
                WHERE users.role = 'visitor'
                GROUP BY users.college
                ORDER BY total DESC";
$college_result = $conn->query($college_sql);

$collegeLabels = [];
$collegeCounts = [];

if ($college_result && $college_result->num_rows > 0) {
    while ($college = $college_result->fetch_assoc()) {
        $collegeLabels[] = $college['college'];
        $collegeCounts[] = (int)$college['total'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | NEU Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: linear-gradient(180deg, #0b1957 0%, #132a73 100%);
            min-height: 100vh;
            color: white;
        }

        .topbar {
            width: 100%;
            min-height: 64px;
            background: #08164d;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 42px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            gap: 12px;
            flex-wrap: wrap;
        }

        .brand {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }

        .btn-logout {
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 18px;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-logout:hover {
            background: #dc2626;
            color: white;
        }

        .container-wrap {
            padding: 30px 24px 40px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .section-title {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .last-updated {
            color: #cbd5e1;
            font-size: 14px;
            margin-bottom: 18px;
        }

        .glass-card {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.18);
            backdrop-filter: blur(3px);
        }

        .stat-card {
            padding: 22px;
            height: 100%;
        }

        .stat-label {
            color: #dbe4ff;
            font-size: 15px;
            margin-bottom: 8px;
        }

        .stat-number {
            font-size: 34px;
            font-weight: 700;
            color: #ffffff;
        }

        .filter-card,
        .table-card,
        .college-card {
            padding: 22px;
        }

        .form-control {
            height: 44px;
            border: none;
            border-radius: 8px;
            background: #f3f4f6;
            color: #111827;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.22) !important;
        }

        .btn-filter {
            height: 44px;
            border: none;
            border-radius: 8px;
            background: #4d8df7;
            color: white;
            font-weight: 600;
            width: 100%;
        }

        .btn-filter:hover {
            background: #3f7ae0;
        }

        .btn-clear {
            height: 44px;
            border: 1px solid #94a3b8;
            border-radius: 8px;
            background: transparent;
            color: white;
            font-weight: 600;
            width: 100%;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-clear:hover {
            background: rgba(255,255,255,0.08);
            color: white;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            color: white;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 10px;
            border-bottom: 1px solid rgba(255,255,255,0.10);
            vertical-align: middle;
            white-space: nowrap;
        }

        th {
            color: #dbe4ff;
            font-weight: 600;
            font-size: 14px;
        }

        td {
            font-size: 14px;
        }

        .status-badge {
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            display: inline-block;
        }

        .status-active {
            background: rgba(34,197,94,0.18);
            color: #bbf7d0;
        }

        .status-blocked {
            background: rgba(239,68,68,0.18);
            color: #fecaca;
        }

        .btn-action {
            padding: 7px 12px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .btn-block {
            background: #ef4444;
            color: white;
        }

        .btn-block:hover {
            background: #dc2626;
            color: white;
        }

        .btn-unblock {
            background: #22c55e;
            color: white;
        }

        .btn-unblock:hover {
            background: #16a34a;
            color: white;
        }

        .college-list {
            margin: 16px 0 0 0;
            padding-left: 18px;
        }

        .college-list li {
            margin-bottom: 8px;
            color: #e5edff;
        }

        .small-muted {
            color: #cbd5e1;
            font-size: 14px;
            margin-bottom: 14px;
        }

        #collegeChart {
            max-height: 260px;
        }
    </style>
</head>
<body>

    <div class="topbar">
        <h1 class="brand">NEU Library</h1>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>

    <div class="container-wrap">
        <div class="section-title">Admin Dashboard</div>
        <div class="last-updated">Last updated: <?php echo date("h:i:s A"); ?></div>

        <div class="row g-3 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="glass-card stat-card">
                    <div class="stat-label">Visitors Today</div>
                    <div class="stat-number"><?php echo $daily; ?></div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="glass-card stat-card">
                    <div class="stat-label">Visitors This Week</div>
                    <div class="stat-number"><?php echo $weekly; ?></div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="glass-card stat-card">
                    <div class="stat-label">Visitors This Month</div>
                    <div class="stat-number"><?php echo $monthly; ?></div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="glass-card stat-card">
                    <div class="stat-label">Total Registered Visitors</div>
                    <div class="stat-number"><?php echo $total_users; ?></div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="glass-card filter-card">
                    <div class="section-title" style="font-size: 22px; margin-bottom: 10px;">Search and Filter</div>
                    <div class="small-muted">Search by full name, email, or college. You can also filter by visit date.</div>

                    <form method="GET" class="row g-3">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control" placeholder="Search name, email, college" value="<?php echo htmlspecialchars($search); ?>">
                        </div>

                        <div class="col-md-4">
                            <input type="date" name="filter_date" class="form-control" value="<?php echo htmlspecialchars($filter_date); ?>">
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn-filter">Apply</button>
                        </div>

                        <div class="col-md-3">
                            <a href="dashboard.php" class="btn-clear">Clear Filter</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-card college-card">
                    <div class="section-title" style="font-size: 22px; margin-bottom: 10px;">Visitors per College</div>

                    <canvas id="collegeChart"></canvas>

                    <ul class="college-list">
                        <?php if (!empty($collegeLabels)): ?>
                            <?php for ($i = 0; $i < count($collegeLabels); $i++): ?>
                                <li><?php echo htmlspecialchars($collegeLabels[$i]); ?> — <?php echo $collegeCounts[$i]; ?></li>
                            <?php endfor; ?>
                        <?php else: ?>
                            <li>No college data yet.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="glass-card table-card">
            <div class="section-title" style="font-size: 22px; margin-bottom: 14px;">Visitor Logs</div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>College</th>
                            <th>Purpose</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['college']); ?></td>
                                    <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                                    <td><?php echo htmlspecialchars($row['visit_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['visit_time']); ?></td>
                                    <td>
                                        <?php if ($row['is_blocked'] == 1): ?>
                                            <span class="status-badge status-blocked">Blocked</span>
                                        <?php else: ?>
                                            <span class="status-badge status-active">Active</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['is_blocked'] == 1): ?>
                                            <a class="btn-action btn-unblock" href="dashboard.php?action=unblock&user_id=<?php echo $row['user_id']; ?>">Unblock</a>
                                        <?php else: ?>
                                            <a class="btn-action btn-block" href="dashboard.php?action=block&user_id=<?php echo $row['user_id']; ?>">Block</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">No visitor logs found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const collegeLabels = <?php echo json_encode($collegeLabels); ?>;
        const collegeCounts = <?php echo json_encode($collegeCounts); ?>;

        const ctx = document.getElementById('collegeChart');

        if (ctx && collegeLabels.length > 0) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: collegeLabels,
                    datasets: [{
                        label: 'Visitors',
                        data: collegeCounts,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white'
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: 'white'
                            },
                            grid: {
                                color: 'rgba(255,255,255,0.08)'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: 'white',
                                precision: 0
                            },
                            grid: {
                                color: 'rgba(255,255,255,0.08)'
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>