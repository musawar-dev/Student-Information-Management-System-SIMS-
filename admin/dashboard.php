<?php
session_start();
include '../db_connect.php';

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'HOD') {
    header("Location: ../login.php");
    exit();
}

// HOD Name Fetch
$u_id = $_SESSION['user_id'];
$u_name = "Admin";
$u_q = $conn->query("SELECT username FROM Users WHERE user_id = '$u_id'");
if($u_q->num_rows > 0) {
    $u_name = ucfirst($u_q->fetch_assoc()['username']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>HOD Dashboard | Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* SIDEBAR PRO DESIGN */
        .sidebar { height: 100vh; width: 260px; position: fixed; top: 0; left: 0; background: linear-gradient(180deg, #212529 0%, #343a40 100%); padding-top: 20px; box-shadow: 4px 0 15px rgba(0,0,0,0.1); z-index: 1000; }
        .sidebar-header { text-align: center; color: white; margin-bottom: 30px; }
        .sidebar-link { padding: 15px 25px; text-decoration: none; font-size: 16px; color: #adb5bd; display: flex; align-items: center; transition: all 0.3s; border-left: 4px solid transparent; }
        .sidebar-link i { width: 30px; }
        .sidebar-link:hover, .sidebar-link.active { background-color: #495057; color: white; border-left: 4px solid #0d6efd; transform: translateX(5px); }
        
        /* LOGOUT BUTTON PRO */
        .logout-container { position: absolute; bottom: 30px; width: 100%; padding: 0 20px; }
        .btn-logout { background: rgba(220, 53, 69, 0.1); color: #ff6b6b; border: 1px solid #dc3545; width: 100%; padding: 10px; border-radius: 8px; font-weight: 600; transition: 0.3s; text-decoration: none; display: block; text-align: center; }
        .btn-logout:hover { background: #dc3545; color: white; transform: translateY(-3px); box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3); }

        .main-content { margin-left: 260px; padding: 30px; }
        
        /* HEADER DESIGN */
        .dashboard-header { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        /* CARDS DESIGN */
        .stat-card { border: none; border-radius: 15px; color: white; position: relative; overflow: hidden; transition: all 0.4s ease; min-height: 140px; }
        .stat-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.2); }
        .stat-card .icon-bg { position: absolute; right: -20px; bottom: -20px; font-size: 100px; opacity: 0.2; transform: rotate(15deg); }
        .card-c1 { background: linear-gradient(45deg, #4e54c8, #8f94fb); } /* Blue */
        .card-c2 { background: linear-gradient(45deg, #11998e, #38ef7d); } /* Green */
        .card-c3 { background: linear-gradient(45deg, #f12711, #f5af19); } /* Orange */
        .card-c4 { background: linear-gradient(45deg, #eb3349, #f45c43); } /* Red */

        /* QUICK ACTIONS */
        .quick-btn { background: white; border: none; border-radius: 12px; padding: 25px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; color: #495057; text-decoration: none; display: block; height: 100%; }
        .quick-btn:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); color: #0d6efd; }
        .quick-btn i { font-size: 2.5rem; margin-bottom: 10px; display: block; }

        /* Add inside <style> tag */

        @media (max-width: 768px) {
            .sidebar { left: -260px; transition: 0.3s; }
            .sidebar.active { left: 0; }
            .main-content { margin-left: 0; padding: 15px; }
            
            /* Mobile Toggle Button dikhane ke liye */
            .mobile-toggle { display: block !important; position: fixed; top: 15px; right: 15px; z-index: 1001; }
        }
        /* Desktop par toggle button chhupana */
        .mobile-toggle { display: none; }
    </style>
</head>
<body>

<button class="btn btn-primary mobile-toggle shadow" onclick="document.querySelector('.sidebar').classList.toggle('active')">
    <i class="fa fa-bars"></i>
</button>

<div class="sidebar">
    <div class="sidebar-header">
        <img src="../uploads/logo.png" alt="University Logo" style="width: 60px; height: 60px; object-fit: contain; background: white; border-radius: 50%; padding: 5px; margin-bottom: 10px;">
        
        <h4 class="mb-0"><i class="fa fa-user-shield me-2"></i> HOD Panel</h4>
    </div>
    <a href="dashboard.php" class="sidebar-link active"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
    <a href="manage_students.php" class="sidebar-link"><i class="fa fa-user-graduate"></i> Students</a>
    <a href="manage_teachers.php" class="sidebar-link"><i class="fa fa-chalkboard-teacher"></i> Teachers</a>
    <a href="manage_courses.php" class="sidebar-link"><i class="fa fa-book"></i> Courses</a>
    <a href="assign_course.php" class="sidebar-link"><i class="fa fa-link"></i> Assign Course</a>
    <a href="system_logs.php" class="sidebar-link"><i class="fa fa-history"></i> System Logs</a>
    
    <div class="logout-container">
        <a href="../logout.php" class="btn-logout"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-0">Welcome, HOD!</h2>
            <p class="text-muted mb-0">Here's your overview.</p>
        </div>

        <form action="search.php" method="GET" class="d-flex shadow-sm rounded-pill overflow-hidden border" style="max-width: 300px;">
            <input type="text" name="q" class="form-control border-0 shadow-none px-3" placeholder="Search..." required>
            <button class="btn btn-primary border-0 rounded-0 px-3"><i class="fa fa-search"></i></button>
        </form>

        <div class="text-end d-none d-md-block">
            <h5 class="fw-bold text-primary mb-0"><?php echo date("l"); ?></h5>
            <small class="text-muted"><?php echo date("d F Y"); ?></small>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card stat-card card-c1 p-4">
                <h3><?php echo $conn->query("SELECT count(*) as t FROM Students")->fetch_assoc()['t']; ?></h3>
                <p class="mb-0 fs-5">Total Students</p>
                <i class="fa fa-user-graduate icon-bg"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card card-c2 p-4">
                <h3><?php echo $conn->query("SELECT count(*) as t FROM Teachers")->fetch_assoc()['t']; ?></h3>
                <p class="mb-0 fs-5">Total Teachers</p>
                <i class="fa fa-chalkboard-teacher icon-bg"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card card-c3 p-4">
                <h3><?php echo $conn->query("SELECT count(*) as t FROM Courses")->fetch_assoc()['t']; ?></h3>
                <p class="mb-0 fs-5">Active Courses</p>
                <i class="fa fa-book icon-bg"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card card-c4 p-4">
                <h3><?php echo $conn->query("SELECT count(*) as t FROM system_logs")->fetch_assoc()['t']; ?></h3>
                <p class="mb-0 fs-5">System Logs</p>
                <i class="fa fa-shield-alt icon-bg"></i>
            </div>
        </div>
    </div>

    <h4 class="mb-4 fw-bold text-secondary">Quick Actions</h4>
    <div class="row g-4">
        <div class="col-md-3">
            <a href="manage_students.php" class="quick-btn">
                <i class="fa fa-user-plus text-primary"></i>
                <h5 class="fw-bold">Add Student</h5>
            </a>
        </div>
        <div class="col-md-3">
            <a href="manage_teachers.php" class="quick-btn">
                <i class="fa fa-chalkboard text-success"></i>
                <h5 class="fw-bold">Add Teacher</h5>
            </a>
        </div>
        <div class="col-md-3">
            <a href="manage_courses.php" class="quick-btn">
                <i class="fa fa-book-open text-warning"></i>
                <h5 class="fw-bold">Add Course</h5>
            </a>
        </div>
        <div class="col-md-3">
            <a href="system_logs.php" class="quick-btn">
                <i class="fa fa-file-contract text-danger"></i>
                <h5 class="fw-bold">View Logs</h5>
            </a>
        </div>
    </div>

</div>

</body>
</html>