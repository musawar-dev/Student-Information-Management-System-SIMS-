<?php
session_start();
include '../db_connect.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Teacher') { header("Location: ../login.php"); exit(); }

$u_id = $_SESSION['user_id'];
$row = $conn->query("SELECT * FROM Teachers WHERE user_id = '$u_id'")->fetch_assoc();
$img = !empty($row['profile_photo']) ? "../uploads/".$row['profile_photo'] : "../uploads/default_teacher.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        /* SIDEBAR FIXED (Flexbox Added) */
        .sidebar { 
            height: 100vh; 
            width: 260px; 
            position: fixed; 
            top: 0; 
            left: 0; 
            background: linear-gradient(180deg, #212529 0%, #343a40 100%); 
            z-index: 1000; 
            transition: all 0.3s;
            
            /* YE 2 LINES ZAROORI HAIN LOGOUT KO NEECHE RAKHNE KE LIYE */
            display: flex; 
            flex-direction: column; 
        }
        
        .sidebar-header { padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-link { padding: 12px 25px; text-decoration: none; color: #adb5bd; display: block; border-left: 4px solid transparent; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(255,255,255,0.1); color: white; border-left: 4px solid #0d6efd; }
        .main-content { margin-left: 260px; padding: 30px; }

        /* MOBILE */
        @media (max-width: 768px) {
            .sidebar { left: -260px; }
            .sidebar.active { left: 0; }
            .main-content { margin-left: 0; }
            .top-navbar { left: 0; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header text-center">
        <img src="../uploads/logo.png" class="d-block mx-auto shadow-sm" 
            style="width: 80px; height: 80px; object-fit: contain; background: white; border-radius: 50%; padding: 5px; margin-bottom: 10px; border: 3px solid rgba(255,255,255,0.2);">
        
        <h5 class="mb-0 fw-bold text-white tracking-wide">Teacher Panel</h5>
    </div>
    <div class="p-2">
        <a href="dashboard.php" class="sidebar-link"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="profile.php" class="sidebar-link active"><i class="fa fa-user me-2"></i> My Profile</a>
        <a href="my_courses.php" class="sidebar-link"><i class="fa fa-book me-2"></i> My Courses</a>
    </div>
    <div class="p-3 mt-auto">
        <a href="../logout.php" class="btn btn-outline-danger w-100"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fa fa-user-circle me-2"></i> My Profile</h4>
        </div>
        <div class="card-body p-5">
            <div class="row align-items-center">
                <div class="col-md-4 text-center">
                    <img src="<?php echo $img; ?>" class="rounded-circle border border-4 border-primary mb-3 shadow" width="180" height="180" style="object-fit: cover;">
                    <h3 class="fw-bold"><?php echo $row['first_name']." ".$row['last_name']; ?></h3>
                    <span class="badge bg-success fs-6"><?php echo $row['designation']; ?></span>
                </div>
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-6"><strong>Email:</strong> <p class="text-muted"><?php echo $row['email']; ?></p></div>
                        <div class="col-6"><strong>Phone:</strong> <p class="text-muted"><?php echo $row['phone_no']; ?></p></div>
                        <div class="col-6"><strong>CNIC:</strong> <p class="text-muted"><?php echo $row['cnic']; ?></p></div>
                        <div class="col-6"><strong>Qualification:</strong> <p class="text-muted"><?php echo $row['qualification']; ?></p></div>
                        <div class="col-12"><strong>Address:</strong> <p class="text-muted"><?php echo $row['address']; ?></p></div>
                        <div class="col-6"><strong>Joining Date:</strong> <p class="text-muted"><?php echo $row['joining_date']; ?></p></div>
                        <div class="col-6"><strong>Base Salary:</strong> <p class="text-muted">Rs. <?php echo number_format($row['salary']); ?></p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>