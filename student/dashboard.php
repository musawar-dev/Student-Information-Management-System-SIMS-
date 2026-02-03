<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Student') { header("Location: ../login.php"); exit(); }

$u_id = $_SESSION['user_id'];

// 1. Fetch Student Details (WITH SAFETY CHECK)
$s_sql = "SELECT Students.*, Batches.batch_name 
          FROM Students 
          LEFT JOIN Batches ON Students.batch_id = Batches.batch_id 
          WHERE Students.user_id = '$u_id'";

$s_res = $conn->query($s_sql);

// --- BUG FIX: Agar Student Found na ho to Error dikhaye ---
if ($s_res->num_rows == 0) {
    die("<div style='padding:50px; text-align:center; font-family:sans-serif;'>
            <h2 style='color:red;'>Profile Error</h2>
            <p>User ID: <b>$u_id</b> ka record 'Students' table mein nahi mila.</p>
            <p>Admin se kahein ke is User ko delete karke dubara <b>'Add Student'</b> karein.</p>
            <a href='../logout.php' style='padding:10px 20px; background:red; color:white; text-decoration:none; border-radius:5px;'>Logout</a>
         </div>");
}

$student = $s_res->fetch_assoc();
$roll_no = $student['roll_no'];
$img = !empty($student['profile_photo']) ? "../uploads/".$student['profile_photo'] : "../uploads/default_student.png";

// --- 2. LOGIC ENGINE (CGPA & ATTENDANCE) ---
$total_gp = 0;
// ... (Baqi code wesa hi rahega)
// --- 2. LOGIC ENGINE (CGPA & ATTENDANCE) ---
$total_gp = 0;
$total_ch = 0;
$attendance_risk = 0;
$active_courses = 0;

$c_sql = "SELECT Enrollments.*, Courses.credit_hours, Courses.course_title 
          FROM Enrollments 
          JOIN Courses ON Enrollments.course_code = Courses.course_code 
          WHERE Enrollments.roll_no = '$roll_no'";
$c_res = $conn->query($c_sql);

if ($c_res->num_rows > 0) {
    while ($row = $c_res->fetch_assoc()) {
        $active_courses++;
        
        // A. ATTENDANCE CALCULATION
        $max_classes = ($row['credit_hours'] == 3) ? 45 : 30; // Your Logic
        $attended = $row['classes_attended'];
        $att_perc = ($max_classes > 0) ? ($attended / $max_classes) * 100 : 0;
        
        if ($att_perc < 75) {
            $attendance_risk++;
        }

        // B. GPA/CGPA CALCULATION
        $obt = $row['sessional_marks'] + $row['mid_marks'] + $row['final_marks'];
        $gp = 0.0;
        
        // Your Grading Table Logic
        if ($obt >= 90) $gp = 4.0;
        elseif ($obt >= 81) $gp = 3.5;
        elseif ($obt >= 73) $gp = 3.0;
        elseif ($obt >= 65) $gp = 2.5;
        elseif ($obt >= 60) $gp = 2.0;
        elseif ($obt >= 55) $gp = 1.5;
        elseif ($obt >= 50) $gp = 1.0;
        else $gp = 0.0;

        // Sirf tab count karein agar marks update hue hon (e.g., > 0)
        if ($obt > 0) {
            $total_gp += ($gp * $row['credit_hours']);
            $total_ch += $row['credit_hours'];
        }
    }
}

// Avoid Division by Zero
$cgpa = ($total_ch > 0) ? round($total_gp / $total_ch, 2) : 0.00;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        
        /* SIDEBAR */
        .sidebar { height: 100vh; width: 260px; position: fixed; top: 0; left: 0; background: linear-gradient(180deg, #212529 0%, #343a40 100%); z-index: 1000; display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-link { padding: 15px 25px; text-decoration: none; color: #adb5bd; display: block; border-left: 4px solid transparent; }
        .sidebar-link:hover, .sidebar-link.active { background: #495057; color: white; border-left: 4px solid #0d6efd; }
        .logout-container { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .btn-logout { background: rgba(220, 53, 69, 0.1); color: #ff6b6b; border: 1px solid #dc3545; display:block; padding:10px; text-align:center; border-radius:8px; text-decoration:none; transition: 0.3s;}
        .btn-logout:hover { background: #dc3545; color: white; }

        .main-content { margin-left: 260px; padding: 30px; }

        /* CARDS */
        .dashboard-card {
            border: none; border-radius: 20px; color: white; padding: 25px;
            position: relative; overflow: hidden; height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .dashboard-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
        
        .card-cgpa { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-att { background: linear-gradient(135deg, #ff9966 0%, #ff5e62 100%); }
        .card-course { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .card-profile { background: white; color: #333; }

        .card-icon-bg { position: absolute; right: -15px; bottom: -15px; font-size: 100px; opacity: 0.15; transform: rotate(15deg); }
        .stat-value { font-size: 2.5rem; font-weight: 800; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h4 class="text-white mb-0"><i class="fa fa-user-graduate me-2"></i> Student Panel</h4>
    </div>
    <div style="flex-grow:1; padding-top: 10px;">
        <a href="dashboard.php" class="sidebar-link active"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="my_attendance.php" class="sidebar-link"><i class="fa fa-calendar-check me-2"></i> My Attendance</a>
        <a href="result_ledger.php" class="sidebar-link"><i class="fa fa-file-alt me-2"></i> Result Ledger</a>
        <a href="my_courses.php" class="sidebar-link"><i class="fa fa-book me-2"></i> My Courses</a>
        <a href="profile.php" class="sidebar-link"><i class="fa fa-id-card me-2"></i> Profile</a>
    </div>
    <div class="logout-container">
        <a href="../logout.php" class="btn-logout"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm">
        <div class="d-flex align-items-center">
            <img src="<?php echo $img; ?>" class="rounded-circle border border-2 border-primary me-3" width="60" height="60" style="object-fit:cover;">
            <div>
                <h4 class="fw-bold mb-0 text-dark">Welcome, <?php echo $student['first_name']; ?>!</h4>
                <p class="text-muted mb-0 small"><?php echo $student['roll_no']; ?> | <?php echo $student['batch_name']; ?></p>
            </div>
        </div>
        <div class="text-end">
            <h5 class="fw-bold text-primary mb-0"><?php echo date("l"); ?></h5>
            <small class="text-muted"><?php echo date("d F Y"); ?></small>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="dashboard-card card-cgpa">
                <i class="fa fa-graduation-cap card-icon-bg"></i>
                <h5>Current CGPA</h5>
                <div class="mt-3">
                    <span class="stat-value"><?php echo number_format($cgpa, 2); ?></span>
                    <span class="fs-5 opacity-75">/ 4.00</span>
                </div>
                <small class="mt-2 d-block opacity-75">Based on <?php echo $total_ch; ?> Credit Hours</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card card-att">
                <i class="fa fa-exclamation-triangle card-icon-bg"></i>
                <h5>Attendance Alerts</h5>
                <div class="mt-3">
                    <span class="stat-value"><?php echo $attendance_risk; ?></span>
                    <span class="fs-5 opacity-75">Subjects</span>
                </div>
                <?php if($attendance_risk > 0): ?>
                    <small class="mt-2 d-block text-warning fw-bold bg-dark bg-opacity-25 px-2 rounded w-auto d-inline-block">Below 75% Limit!</small>
                <?php else: ?>
                    <small class="mt-2 d-block text-white opacity-75">All clear! Keep it up.</small>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card card-course">
                <i class="fa fa-book-open card-icon-bg"></i>
                <h5>Active Courses</h5>
                <div class="mt-3">
                    <span class="stat-value"><?php echo $active_courses; ?></span>
                    <span class="fs-5 opacity-75">Subjects</span>
                </div>
                <small class="mt-2 d-block opacity-75">Current Semester</small>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="fw-bold"><i class="fa fa-bell me-2 text-primary"></i> Notifications</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="alert alert-light border-start border-4 border-info shadow-sm">
                        <strong><i class="fa fa-info-circle me-2"></i> Info:</strong> 
                        Welcome to your new Student Portal. Check your attendance regularly to avoid penalties.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>