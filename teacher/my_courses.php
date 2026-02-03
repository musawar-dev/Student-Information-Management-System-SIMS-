<?php
session_start();
include '../db_connect.php'; 

// Check Login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Teacher') { 
    header("Location: ../login.php"); 
    exit(); 
}

// 1. Logged-in Teacher ki Employee ID nikalo
$user_id = $_SESSION['user_id'];
$emp_query = $conn->query("SELECT employee_id FROM Teachers WHERE user_id = '$user_id'");
$emp_row = $emp_query->fetch_assoc();
$emp_id = $emp_row['employee_id'];


// 2. LEFT JOIN FIX (Taake agar link toota bhi ho to Course nazar aye)
$sql = "SELECT 
            Allocations.allocation_id,
            Allocations.section,
            Allocations.batch_id,        -- <-- YAHAN GOR KAREN (Allocations se ID le rahe hain)
            Courses.course_title,
            Courses.course_code,
            Batches.batch_name
        FROM Allocations 
        INNER JOIN Courses ON Allocations.course_code = Courses.course_code 
        INNER JOIN Batches ON Allocations.batch_id = Batches.batch_id  -- <-- Yahan Link jor rahe hain
        WHERE Allocations.employee_id = '$emp_id'";

$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Courses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f5f7fa; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        .sidebar { 
            height: 100vh; 
            width: 260px; 
            position: fixed; 
            top: 0; 
            left: 0; 
            background: linear-gradient(180deg, #212529 0%, #343a40 100%); 
            z-index: 1000; 
            transition: all 0.3s;
            display: flex; 
            flex-direction: column; 
        }
        
        .sidebar-header { padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-link { padding: 12px 25px; text-decoration: none; color: #adb5bd; display: block; border-left: 4px solid transparent; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(255,255,255,0.1); color: white; border-left: 4px solid #0d6efd; }
        
        /* CONTENT */
        .main-content { margin-left: 260px; padding: 30px; margin-top: 70px; transition: 0.3s; }
        .top-navbar { position: fixed; top: 0; left: 260px; right: 0; height: 70px; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); z-index: 999; display: flex; align-items: center; padding: 0 30px; justify-content: space-between; transition: 0.3s; }
        
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

<div class="sidebar" id="sidebar">
    <div class="sidebar-header text-center">
        <img src="../uploads/logo.png" class="d-block mx-auto shadow-sm" 
            style="width: 80px; height: 80px; object-fit: contain; background: white; border-radius: 50%; padding: 5px; margin-bottom: 10px; border: 3px solid rgba(255,255,255,0.2);">
        
        <h5 class="mb-0 fw-bold text-white tracking-wide">Teacher Panel</h5>
    </div>
    
    <div class="p-2">
        <a href="dashboard.php" class="sidebar-link"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="profile.php" class="sidebar-link"><i class="fa fa-user me-2"></i> My Profile</a>
        <a href="my_courses.php" class="sidebar-link active"><i class="fa fa-book me-2"></i> My Courses</a>
    </div>

    <div class="p-3 mt-auto">
        <a href="../logout.php" class="btn btn-outline-danger w-100"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="top-navbar">
    <button class="btn btn-light d-md-none" onclick="document.getElementById('sidebar').classList.toggle('active')">
        <i class="fa fa-bars"></i>
    </button>
    
    <h4 class="mb-0 fw-bold">My Courses</h4>
    <div class="d-none d-md-block text-muted">Manage your classes here</div>
</div>

<div class="main-content">
    <div class="row g-4">
        <?php
        if($res && $res->num_rows > 0){
            while($row = $res->fetch_assoc()){
                
                // --- ERROR DETECTION LOGIC ---
                $has_error = false;
                $error_msg = "";

                // Check 1: Course Name mila ya nahi?
                if(empty($row['course_title'])){
                    $display_title = "INVALID COURSE";
                    $display_code = "Code: " . $row['course_code'] . " (Not Found)";
                    $has_error = true;
                } else {
                    $display_title = $row['course_title'];
                    $display_code = $row['course_code'] . ' | Sec ' . $row['section'];
                }

                // Check 2: Batch Name mila ya nahi?
                if(empty($row['batch_name'])){
                    // Agar Batch Name nahi mila, to ID dikhao taake pata chale database me kya hai
                    $display_batch = "<span class='text-danger fw-bold'><i class='fa fa-exclamation-triangle'></i> Invalid Batch (ID: ".$row['batch_id'].")</span>";
                    $has_error = true;
                } else {
                    $display_batch = "<strong>Batch:</strong> " . $row['batch_name'];
                }

                // Links
                $att_link = "take_attendance.php?code=".$row['course_code']."&batch_id=".$row['batch_id'];
                $marks_link = "manage_marks.php?code=".$row['course_code']."&sec=".$row['section']."&batch_id=".$row['batch_id'];
                
                // Card Color change agar Error ho
                $card_header_class = $has_error ? "bg-danger" : "bg-primary";
                
                echo '<div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                        <div class="card-header '.$card_header_class.' text-white p-3">
                            <h5 class="mb-0 fw-bold">'.$display_title.'</h5>
                            <small class="opacity-75">'.$display_code.'</small>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <p class="text-muted mb-4">
                                <i class="fa fa-users me-2 text-primary"></i> 
                                '.$display_batch.'
                            </p>
                            
                            <div class="mt-auto d-grid gap-2">';
                                
                                if($has_error){
                                    echo '<button class="btn btn-secondary" disabled>Fix in Admin Panel</button>';
                                } else {
                                    echo '<a href="'.$att_link.'" class="btn btn-success fw-bold">
                                            <i class="fa fa-calendar-check me-2"></i> Take Attendance
                                          </a>
                                          <a href="'.$marks_link.'" class="btn btn-outline-primary fw-bold">
                                            <i class="fa fa-edit me-2"></i> Manage Marks
                                          </a>';
                                }

                echo '      </div>
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo '<div class="col-12">
                    <div class="alert alert-warning text-center p-5 rounded-4">
                        <h4>No Courses Found</h4>
                        <p>Dashboard says 3, but Query found 0. Check Database Connection.</p>
                    </div>
                  </div>';
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>