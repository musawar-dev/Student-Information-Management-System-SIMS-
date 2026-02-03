<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'HOD') { header("Location: ../login.php"); exit(); }

$message = "";

// --- CLEAR LOGS ---
if (isset($_POST['clear_logs'])) {
    if ($conn->query("TRUNCATE TABLE system_logs")) {
        // Clear ke baad entry dalein ke kisne clear kiya
        log_activity($_SESSION['user_id'], 'HOD', 'RESET', 'Logs Cleared by Admin');
        $message = "<div class='alert alert-success'>System Logs Cleared Successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>System Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* PRO SIDEBAR CSS */
        .sidebar { height: 100vh; width: 260px; position: fixed; top: 0; left: 0; background: linear-gradient(180deg, #212529 0%, #343a40 100%); z-index: 1000; display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; text-align: center; color: white; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-menu { flex-grow: 1; overflow-y: auto; padding-top: 10px; }
        .sidebar-link { padding: 15px 25px; text-decoration: none; font-size: 16px; color: #adb5bd; display: block; border-left: 4px solid transparent; }
        .sidebar-link:hover, .sidebar-link.active { background-color: #495057; color: white; border-left: 4px solid #0d6efd; }
        .logout-container { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .btn-logout { background: rgba(220, 53, 69, 0.1); color: #ff6b6b; border: 1px solid #dc3545; display:block; padding:10px; text-align:center; border-radius:8px; text-decoration:none; transition: 0.3s;}
        .btn-logout:hover { background: #dc3545; color: white; }

        .main-content { margin-left: 260px; padding: 30px; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <img src="../uploads/logo.png" alt="University Logo" style="width: 60px; height: 60px; object-fit: contain; background: white; border-radius: 50%; padding: 5px; margin-bottom: 10px;">
        
        <h4 class="mb-0"><i class="fa fa-user-shield me-2"></i> HOD Panel</h4>
    </div>
    <div class="sidebar-menu">
        <a href="dashboard.php" class="sidebar-link"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="manage_students.php" class="sidebar-link"><i class="fa fa-user-graduate me-2"></i> Students</a>
        <a href="manage_teachers.php" class="sidebar-link"><i class="fa fa-chalkboard-teacher me-2"></i> Teachers</a>
        <a href="manage_courses.php" class="sidebar-link"><i class="fa fa-book me-2"></i> Courses</a>
        <a href="assign_course.php" class="sidebar-link"><i class="fa fa-link me-2"></i> Assign Course</a>
        <a href="system_logs.php" class="sidebar-link active"><i class="fa fa-history me-2"></i> System Logs</a>
    </div>
    <div class="logout-container">
        <a href="../logout.php" class="btn-logout"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">System Activity Logs</h2>
        <form method="POST" onsubmit="return confirm('WARNING: Are you sure you want to delete ALL logs?');">
            <button type="submit" name="clear_logs" class="btn btn-danger shadow-sm">
                <i class="fa fa-trash me-2"></i> Clear History
            </button>
        </form>
    </div>

    <?php echo $message; ?>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="ps-4">Time</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th class="text-end pe-4">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT system_logs.*, Users.username 
                            FROM system_logs 
                            LEFT JOIN Users ON system_logs.user_id = Users.user_id 
                            ORDER BY system_logs.log_id DESC LIMIT 100";
                            
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            // Badge Colors
                            $badge = 'bg-secondary';
                            if($row['action'] == 'LOGIN') $badge = 'bg-success';
                            if($row['action'] == 'LOGOUT') $badge = 'bg-dark';
                            if($row['action'] == 'DELETE') $badge = 'bg-danger';
                            if($row['action'] == 'ADD') $badge = 'bg-primary';
                            if($row['action'] == 'UPDATE') $badge = 'bg-warning text-dark';
                            
                            $u_name = $row['username'] ? ucfirst($row['username']) : "Unknown";
                            $time = date("d-M h:i A", strtotime($row['timestamp']));

                            echo "<tr>
                                    <td class='ps-4 text-muted small'>$time</td>
                                    <td><strong>$u_name</strong></td>
                                    <td><span class='badge bg-light text-dark border'>".$row['role']."</span></td>
                                    <td><span class='badge $badge'>".$row['action']."</span></td>
                                    <td>".$row['description']."</td>
                                    <td class='text-end pe-4 small text-muted'>".$row['ip_address']."</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center p-5 text-muted'>No activity recorded yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>