<?php
session_start();
include '../db_connect.php';

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Clerk') { header("Location: ../login.php"); exit(); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Clerk Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        /* --- SIDEBAR --- */
        .sidebar { 
            height: 100vh; width: 260px; position: fixed; top: 0; left: 0; 
            background: linear-gradient(180deg, #212529 0%, #343a40 100%); 
            z-index: 1000; transition: all 0.3s; 
            display: flex; flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar-header { 
            padding: 25px 20px; text-align: center; 
            background: rgba(0,0,0,0.2); border-bottom: 1px solid rgba(255,255,255,0.05); 
        }
        .sidebar-link { 
            padding: 15px 25px; text-decoration: none; color: #adb5bd; 
            display: block; border-left: 4px solid transparent; transition: 0.3s; font-weight: 500;
        }
        .sidebar-link:hover, .sidebar-link.active { 
            background: rgba(255,255,255,0.1); color: white; border-left: 4px solid #0d6efd; 
        }

        /* --- CONTENT --- */
        .main-content { margin-left: 260px; padding: 30px; margin-top: 70px; transition: 0.3s; }
        .top-navbar { 
            position: fixed; top: 0; left: 260px; right: 0; height: 70px; 
            background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
            z-index: 999; display: flex; align-items: center; padding: 0 30px; 
            justify-content: space-between; transition: 0.3s; 
        }

        /* --- PRO CARDS --- */
        .dashboard-card {
            border: none; border-radius: 20px; color: white; padding: 25px;
            position: relative; overflow: hidden; height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .dashboard-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
        
        .card-students { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-teachers { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .card-staff { background: linear-gradient(135deg, #ff9966 0%, #ff5e62 100%); }
        .card-att { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); }

        .card-icon-bg { 
            position: absolute; right: -10px; bottom: -10px; 
            font-size: 100px; opacity: 0.15; transform: rotate(15deg); 
        }
        .stat-value { font-size: 2.5rem; font-weight: 800; }

        /* --- MOBILE --- */
        @media (max-width: 768px) {
            .sidebar { left: -260px; }
            .sidebar.active { left: 0; }
            .main-content { margin-left: 0; }
            .top-navbar { left: 0; }
        }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="../uploads/logo.png" class="d-block mx-auto shadow-sm" 
             style="width: 80px; height: 80px; object-fit: contain; background: white; border-radius: 50%; padding: 5px; margin-bottom: 10px; border: 3px solid rgba(255,255,255,0.2);">
        <h5 class="mb-0 fw-bold text-white tracking-wide">Clerk Panel</h5>
    </div>
    
    <div class="p-2">
        <a href="dashboard.php" class="sidebar-link active"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="teacher_attendance.php" class="sidebar-link"><i class="fa fa-chalkboard-teacher me-2"></i> Teacher Attendance</a>
        <a href="manage_staff.php" class="sidebar-link"><i class="fa fa-broom me-2"></i> Manage Staff</a>
        <a href="reset_password.php" class="sidebar-link"><i class="fa fa-key me-2"></i> Reset Passwords</a>
        <a href="reports.php" class="sidebar-link"><i class="fa fa-file-excel me-2"></i> Reports & Export</a>
    </div>

    <div class="p-3 mt-auto">
        <a href="../logout.php" class="btn btn-outline-danger w-100 fw-bold"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="top-navbar">
    <button class="btn btn-light d-md-none shadow-sm" onclick="document.getElementById('sidebar').classList.toggle('active')">
        <i class="fa fa-bars"></i>
    </button>
    <h4 class="mb-0 fw-bold text-dark">Dashboard</h4>
    <div class="d-none d-md-block text-muted">University Management System</div>
</div>

<div class="main-content">
    
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm">
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3 text-primary">
                <i class="fa fa-user-tie fa-2x"></i>
            </div>
            <div>
                <h3 class="fw-bold mb-0">Welcome Back, Clerk!</h3>
                <p class="text-muted mb-0">Here is your daily overview.</p>
            </div>
        </div>
        
        <div class="text-end d-none d-md-block border-start ps-4">
            <h5 class="fw-bold text-primary mb-0"><?php echo date("l"); ?></h5> <small class="text-muted fw-bold"><?php echo date("d F Y"); ?></small> </div>
    </div>

    <div class="row g-4">
        
        <div class="col-md-3">
            <div class="dashboard-card card-students">
                <i class="fa fa-user-graduate card-icon-bg"></i>
                <h5>Total Students</h5>
                <div class="mt-3">
                    <span class="stat-value"><?php echo $conn->query("SELECT count(*) as c FROM Students")->fetch_assoc()['c']; ?></span>
                </div>
                <small class="opacity-75">Registered in System</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card card-teachers">
                <i class="fa fa-chalkboard-teacher card-icon-bg"></i>
                <h5>Total Teachers</h5>
                <div class="mt-3">
                    <span class="stat-value"><?php echo $conn->query("SELECT count(*) as c FROM Teachers")->fetch_assoc()['c']; ?></span>
                </div>
                <small class="opacity-75">Active Faculty</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card card-staff">
                <i class="fa fa-broom card-icon-bg"></i>
                <h5>Support Staff</h5>
                <div class="mt-3">
                    <span class="stat-value"><?php echo $conn->query("SELECT count(*) as c FROM Staff")->fetch_assoc()['c']; ?></span>
                </div>
                <small class="opacity-75">Peons, Sweepers, etc.</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card card-att">
                <i class="fa fa-check-circle card-icon-bg"></i>
                <h5>Teachers Present</h5>
                <div class="mt-3">
                    <?php 
                        $today = date("Y-m-d");
                        $marked = $conn->query("SELECT count(*) as c FROM Teacher_Attendance WHERE attendance_date='$today' AND status='Present'")->fetch_assoc()['c'];
                    ?>
                    <span class="stat-value"><?php echo $marked; ?></span>
                </div>
                <small class="opacity-75">Marked Today</small>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>