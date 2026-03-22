<?php
session_start();
date_default_timezone_set('Asia/Manila');
include 'db.php'; // This provides $pdo

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// --- Action Handling ---
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    $user_id = (int) $_GET['user_id'];
    $action = $_GET['action'];
    $is_blocked = ($action === 'block') ? 1 : 0;

    // Fixed: Changed $conn to $pdo and used PDO syntax
    $sql_update = "UPDATE users SET is_blocked = :block WHERE id = :id AND role = 'visitor'";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute(['block' => $is_blocked, 'id' => $user_id]);

    header("Location: dashboard.php");
    exit();
}

$today = date("Y-m-d");
$currentMonth = date("m");
$currentYear = date("Y");

// --- Statistics (Converted to PDO) ---
$daily_stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM visit_logs WHERE visit_date = ?");
$daily_stmt->execute([$today]);
$daily = $daily_stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// PostgreSQL doesn't have YEARWEEK, so we use date_trunc
$weekly_query = $pdo->query("SELECT COUNT(*) AS total FROM visit_logs WHERE date_trunc('week', visit_date) = date_trunc('week', now())");
$weekly = $weekly_query->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

$monthly_stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM visit_logs WHERE EXTRACT(MONTH FROM visit_date) = ? AND EXTRACT(YEAR FROM visit_date) = ?");
$monthly_stmt->execute([(int)$currentMonth, (int)$currentYear]);
$monthly = $monthly_stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

$total_users_query = $pdo->query("SELECT COUNT(*) AS total FROM users WHERE role = 'visitor'");
$total_users = $total_users_query->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// --- Search and Filter ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$from_date = isset($_GET['from_date']) ? trim($_GET['from_date']) : '';
$to_date = isset($_GET['to_date']) ? trim($_GET['to_date']) : '';

$sql = "SELECT visit_logs.*, users.full_name, users.email, users.college, users.visitor_type, users.is_blocked, users.id AS user_id
        FROM visit_logs
        INNER JOIN users ON visit_logs.user_id = users.id
        WHERE users.role = 'visitor'";

$params = [];

if ($search !== '') {
    // ILIKE is used for case-insensitive search in Postgres
    $sql .= " AND (users.full_name ILIKE ? OR users.email ILIKE ? OR users.college ILIKE ? OR users.visitor_type ILIKE ? OR visit_logs.purpose ILIKE ?)";
    $search_like = "%{$search}%";
    for($i=0; $i<5; $i++) $params[] = $search_like;
}

if ($from_date !== '' && $to_date !== '') {
    $sql .= " AND visit_logs.visit_date BETWEEN ? AND ?";
    $params[] = $from_date;
    $params[] = $to_date;
}

$sql .= " ORDER BY visit_logs.visit_date DESC, visit_logs.visit_time DESC, visit_logs.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetching as an array
$total_results = count($result);

// --- College Data ---
$college_sql = "SELECT users.college, COUNT(visit_logs.id) AS total
                FROM visit_logs
                INNER JOIN users ON visit_logs.user_id = users.id
                WHERE users.role = 'visitor'
                GROUP BY users.college
                ORDER BY total DESC";
$college_result = $pdo->query($college_sql)->fetchAll(PDO::FETCH_ASSOC);

// --- Activity Data ---
$activity_sql = "SELECT users.full_name, visit_logs.visit_time, visit_logs.visit_date
                 FROM visit_logs
                 INNER JOIN users ON visit_logs.user_id = users.id
                 ORDER BY visit_logs.id DESC
                 LIMIT 5";
$activity_result = $pdo->query($activity_sql)->fetchAll(PDO::FETCH_ASSOC);

$collegeLabels = [];
$collegeCounts = [];
foreach ($college_result as $college) {
    $collegeLabels[] = !empty($college['college']) ? $college['college'] : 'N/A';
    $collegeCounts[] = (int)$college['total'];
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
    </head>
<body>

    <div class="container-wrap">
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="glass-card recent-card mt-3">
                    <div class="section-title" style="font-size: 20px; margin-bottom: 10px;">Recent Activity</div>
                    <ul class="recent-list">
                        <?php if (count($activity_result) > 0): ?>
                            <?php foreach ($activity_result as $act): ?>
                                <li>
                                    <strong><?php echo htmlspecialchars($act['full_name']); ?></strong>
                                    <span>checked in — <?php echo date("h:i A", strtotime($act['visit_time'])); ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>No recent activity.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4">
                </div>
        </div>

        <div class="glass-card table-card">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Full Name</th><th>Email</th><th>College</th><th>Visitor Type</th><th>Purpose</th><th>Date</th><th>Time</th><th>Status</th><th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($total_results > 0): ?>
                            <?php foreach ($result as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo !empty($row['college']) ? htmlspecialchars($row['college']) : 'N/A'; ?></td>
                                    <td><?php echo htmlspecialchars($row['visitor_type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                                    <td><?php echo htmlspecialchars($row['visit_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['visit_time']); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo ($row['is_blocked'] == 1) ? 'status-blocked' : 'status-active'; ?>">
                                            <?php echo ($row['is_blocked'] == 1) ? 'Blocked' : 'Active'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($row['is_blocked'] == 1): ?>
                                            <a class="btn-action btn-unblock" href="dashboard.php?action=unblock&user_id=<?php echo $row['user_id']; ?>">Unblock</a>
                                        <?php else: ?>
                                            <a class="btn-action btn-block" href="dashboard.php?action=block&user_id=<?php echo $row['user_id']; ?>">Block</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="9">No visitor logs found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </body>
</html>
