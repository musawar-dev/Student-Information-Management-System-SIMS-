<?php
session_start();
include '../db_connect.php';

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Clerk') { header("Location: ../login.php"); exit(); }

$msg = "";

// --- RESET LOGIC ---
if(isset($_POST['reset_pass'])){
    $roll_no = $conn->real_escape_string($_POST['roll_no']);
    
    // 1. GENERATE RANDOM 5-DIGIT NUMBER
    $random_pass = rand(10000, 99999); 
    
    // 2. CREATE BCRYPT HASH (Ye aapke system ka pattern hai)
    // Ye '$2y$10$...' wala code banaye ga
    $hashed_pass = password_hash($random_pass, PASSWORD_DEFAULT);

    // 3. Find Student
    $sql = "SELECT * FROM Students WHERE roll_no = '$roll_no'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $student = $result->fetch_assoc();
        $u_id = $student['user_id'];
        $name = $student['first_name'] . " " . $student['surname'];

        // 4. Update Password in Users Table
        $update = "UPDATE Users SET password = '$hashed_pass' WHERE user_id = '$u_id'";
        
        if($conn->query($update)){
            $msg = "<div class='alert alert-success alert-dismissible fade show border-0 shadow-sm'>
                        <h4 class='alert-heading'><i class='fa fa-check-circle me-2'></i> Password Reset Successful!</h4>
                        <hr>
                        <p class='mb-1'><strong>Student:</strong> $name ($roll_no)</p>
                        <div class='d-flex align-items-center mt-2'>
                            <span class='me-2'>New Password:</span>
                            <h2 class='badge bg-dark mb-0 px-3 py-2 text-warning tracking-wide' id='newPass'>$random_pass</h2>
                            <button class='btn btn-sm btn-outline-success ms-3' onclick='copyPass()'><i class='fa fa-copy'></i> Copy</button>
                        </div>
                        <p class='mb-0 mt-2 text-muted small'>Easy to remember 5-digit PIN.</p>
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                    </div>";
        } else {
            $msg = "<div class='alert alert-danger'>Error updating password: " . $conn->error . "</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger alert-dismissible fade show border-0 shadow-sm'>
                    <i class='fa fa-times-circle me-2'></i> Student with Roll No <b>$roll_no</b> not found.
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        /* SIDEBAR */
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

        /* CONTENT */
        .main-content { margin-left: 260px; padding: 30px; margin-top: 70px; transition: 0.3s; }
        .top-navbar { 
            position: fixed; top: 0; left: 260px; right: 0; height: 70px; 
            background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
            z-index: 999; display: flex; align-items: center; padding: 0 30px; 
            justify-content: space-between; transition: 0.3s; 
        }

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
        <a href="dashboard.php" class="sidebar-link"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="teacher_attendance.php" class="sidebar-link"><i class="fa fa-chalkboard-teacher me-2"></i> Teacher Attendance</a>
        <a href="manage_staff.php" class="sidebar-link"><i class="fa fa-broom me-2"></i> Manage Staff</a>
        <a href="reset_password.php" class="sidebar-link active"><i class="fa fa-key me-2"></i> Reset Passwords</a>
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
    <h4 class="mb-0 fw-bold text-dark">Password Recovery</h4>
</div>

<div class="main-content">
    
    <div class="container" style="max-width: 600px;">
        
        <?php echo $msg; ?>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden mt-4">
            <div class="card-header bg-warning p-4 text-center text-dark">
                <i class="fa fa-shield-alt fa-3x mb-2 opacity-75"></i>
                <h4 class="fw-bold mb-0">Reset Student Password</h4>
                <p class="mb-0 opacity-75">Generates a simple 5-digit PIN.</p>
            </div>
            
            <div class="card-body p-5">
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted">Student Roll Number</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0"><i class="fa fa-id-card text-muted"></i></span>
                            <input type="text" name="roll_no" class="form-control border-start-0 bg-light" placeholder="e.g. 25SW05" required>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="reset_pass" class="btn btn-warning btn-lg fw-bold shadow-sm">
                            <i class="fa fa-sync-alt me-2"></i> Generate New PIN
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>

<script>
function copyPass() {
    var copyText = document.getElementById("newPass");
    navigator.clipboard.writeText(copyText.innerText);
    alert("PIN Copied: " + copyText.innerText);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>