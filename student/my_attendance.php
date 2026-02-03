<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Student') { header("Location: ../login.php"); exit(); }

$u_id = $_SESSION['user_id'];

// 1. Fetch Student Details
$s_sql = "SELECT roll_no FROM Students WHERE user_id = '$u_id'";
$s_res = $conn->query($s_sql);

// Safety Check
if($s_res->num_rows == 0) { die("Student Record Not Found. Contact Admin."); }

$student = $s_res->fetch_assoc();
$roll_no = $student['roll_no'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        
        /* SIDEBAR (Same as Dashboard) */
        .sidebar { height: 100vh; width: 260px; position: fixed; top: 0; left: 0; background: linear-gradient(180deg, #212529 0%, #343a40 100%); z-index: 1000; display: flex; flex-direction: column; }
        .sidebar-link { padding: 15px 25px; text-decoration: none; color: #adb5bd; display: block; border-left: 4px solid transparent; }
        .sidebar-link:hover, .sidebar-link.active { background: #495057; color: white; border-left: 4px solid #0d6efd; }
        .main-content { margin-left: 260px; padding: 30px; }
        .logout-container { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .btn-logout { background: rgba(220, 53, 69, 0.1); color: #ff6b6b; border: 1px solid #dc3545; display:block; padding:10px; text-align:center; border-radius:8px; text-decoration:none; transition: 0.3s;}
        .btn-logout:hover { background: #dc3545; color: white; }

        /* ATTENDANCE CARD DESIGN */
        .att-card {
            background: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
            overflow: hidden;
            position: relative;
        }
        .att-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        
        .progress { height: 15px; border-radius: 10px; background-color: #e9ecef; margin-top: 15px; }
        .att-badge { position: absolute; top: 20px; right: 20px; padding: 5px 12px; border-radius: 20px; font-weight: bold; font-size: 0.8rem; }
        
        .safe-zone { background-color: #d1e7dd; color: #0f5132; }
        .danger-zone { background-color: #f8d7da; color: #842029; }
        .warning-zone { background-color: #fff3cd; color: #664d03; }
    </style>
</head>
<body>

<div class="sidebar">
    <div style="padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <h4 class="text-white mb-0"><i class="fa fa-user-graduate me-2"></i> Student Panel</h4>
    </div>
    <div style="flex-grow:1; padding-top: 10px;">
        <a href="dashboard.php" class="sidebar-link"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="my_attendance.php" class="sidebar-link active"><i class="fa fa-calendar-check me-2"></i> My Attendance</a>
        <a href="result_ledger.php" class="sidebar-link"><i class="fa fa-file-alt me-2"></i> Result Ledger</a>
        <a href="my_courses.php" class="sidebar-link"><i class="fa fa-book me-2"></i> My Courses</a>
        <a href="profile.php" class="sidebar-link"><i class="fa fa-id-card me-2"></i> Profile</a>
    </div>
    <div class="logout-container">
        <a href="../logout.php" class="btn-logout"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <h3 class="fw-bold mb-4 text-dark"><i class="fa fa-chart-pie me-2"></i> Attendance Tracker</h3>

    <div class="row g-4">
        <?php
        // Fetch Enrollments + Course Details
        $sql = "SELECT Enrollments.*, Courses.course_title, Courses.course_code, Courses.credit_hours 
                FROM Enrollments 
                JOIN Courses ON Enrollments.course_code = Courses.course_code 
                WHERE Enrollments.roll_no = '$roll_no'";
        
        $res = $conn->query($sql);

        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                
                // --- LOGIC ENGINE ---
                // 1. Total Classes based on Credit Hours
                $total_classes = ($row['credit_hours'] == 3) ? 45 : 30;
                
                // 2. Attended Classes (Database se)
                $attended = $row['classes_attended'];
                
                // 3. Percentage Calculation
                $perc = ($total_classes > 0) ? round(($attended / $total_classes) * 100) : 0;
                
                // 4. Color & Status Logic
                if ($perc >= 75) {
                    $color = "bg-success";
                    $status = "Safe Zone";
                    $badge_class = "safe-zone";
                    $msg = "You are doing great! Keep it up.";
                } elseif ($perc >= 60) {
                    $color = "bg-warning";
                    $status = "Warning";
                    $badge_class = "warning-zone";
                    $msg = "Be careful! You are near the limit.";
                } else {
                    $color = "bg-danger";
                    $status = "Critical";
                    $badge_class = "danger-zone";
                    $msg = "Attendance Short! Please attend classes.";
                }

                // 5. "Mooj" Logic (Remaining Leaves)
                // 75% Attendance means 25% Leaves allowed
                // Example 3CH: 45 * 0.75 = 33.75 (34 classes must attend) -> 11 leaves allowed.
                $min_required = ceil($total_classes * 0.75);
                $leaves_allowed = $total_classes - $min_required;
                $leaves_taken = $total_classes - $attended; // Assuming unrecorded classes are future/leaves
                // Better Logic: Leaves Remaining based on current attended
                // Agar abhi tak classes puri nahi hui hain, to logic thora complex hota hai.
                // Simple Logic for Student View:
                // "You must attend X more classes to be safe" logic is better but complex.
                // Hum simple dikhayenge: Present / Total
                
                echo '
                <div class="col-md-6 col-lg-4">
                    <div class="att-card p-4 h-100">
                        <span class="att-badge '.$badge_class.'">'.$status.'</span>
                        
                        <h5 class="fw-bold mt-2">'.$row['course_title'].'</h5>
                        <p class="text-muted small mb-3">'.$row['course_code'].' | '.$row['credit_hours'].' CH</p>
                        
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <h2 class="mb-0 fw-bold">'.$perc.'%</h2>
                                <small class="text-muted">Attendance</small>
                            </div>
                            <div class="text-end">
                                <span class="fs-5 fw-bold text-dark">'.$attended.'</span> <span class="text-muted">/ '.$total_classes.'</span>
                                <div class="small text-muted">Classes</div>
                            </div>
                        </div>

                        <div class="progress">
                            <div class="progress-bar '.$color.' progress-bar-striped progress-bar-animated" role="progressbar" style="width: '.$perc.'%"></div>
                        </div>
                        
                        <div class="mt-3 pt-3 border-top d-flex align-items-center">
                            <i class="fa fa-info-circle text-muted me-2"></i>
                            <small class="text-muted">'.$msg.'</small>
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo '<div class="col-12"><div class="alert alert-info text-center">No courses enrolled yet.</div></div>';
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>