<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Student') { header("Location: ../login.php"); exit(); }

$u_id = $_SESSION['user_id'];

// 1. Fetch Student Details
$s_res = $conn->query("SELECT roll_no, batch_id FROM Students WHERE user_id = '$u_id'");
$student = $s_res->fetch_assoc();
$roll_no = $student['roll_no'];

// 2. Filter Logic (Default: Latest Enrolled Semester)
$selected_sem = isset($_GET['sem']) ? $_GET['sem'] : '';

if(empty($selected_sem)){
    // Get max semester from enrolled courses
    $max_q = $conn->query("SELECT MAX(Courses.semester) as max_sem FROM Enrollments 
                           JOIN Courses ON Enrollments.course_code = Courses.course_code 
                           WHERE Enrollments.roll_no = '$roll_no'");
    $row_max = $max_q->fetch_assoc();
    $selected_sem = ($row_max['max_sem']) ? $row_max['max_sem'] : 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .sidebar { height: 100vh; width: 260px; position: fixed; top: 0; left: 0; background: linear-gradient(180deg, #212529 0%, #343a40 100%); z-index: 1000; display: flex; flex-direction: column; }
        .sidebar-link { padding: 15px 25px; text-decoration: none; color: #adb5bd; display: block; border-left: 4px solid transparent; }
        .sidebar-link:hover, .sidebar-link.active { background: #495057; color: white; border-left: 4px solid #0d6efd; }
        .main-content { margin-left: 260px; padding: 30px; }
        .logout-container { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .btn-logout { background: rgba(220, 53, 69, 0.1); color: #ff6b6b; border: 1px solid #dc3545; display:block; padding:10px; text-align:center; border-radius:8px; text-decoration:none; transition: 0.3s;}
        .btn-logout:hover { background: #dc3545; color: white; }

        /* COURSE CARD */
        .course-card {
            background: white; border: none; border-radius: 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s;
            border-left: 5px solid #0d6efd;
        }
        .course-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .teacher-badge { background: #e9ecef; color: #495057; padding: 5px 10px; border-radius: 20px; font-size: 0.85rem; }
    </style>
</head>
<body>

<div class="sidebar">
    <div style="padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <h4 class="text-white mb-0"><i class="fa fa-user-graduate me-2"></i> Student Panel</h4>
    </div>
    <div style="flex-grow:1; padding-top: 10px;">
        <a href="dashboard.php" class="sidebar-link"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="my_attendance.php" class="sidebar-link"><i class="fa fa-calendar-check me-2"></i> My Attendance</a>
        <a href="result_ledger.php" class="sidebar-link"><i class="fa fa-file-invoice me-2"></i> Result Ledger</a>
        <a href="my_courses.php" class="sidebar-link active"><i class="fa fa-book me-2"></i> My Courses</a>
        <a href="profile.php" class="sidebar-link"><i class="fa fa-id-card me-2"></i> Profile</a>
    </div>
    <div class="logout-container">
        <a href="../logout.php" class="btn-logout"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0"><i class="fa fa-book-reader me-2"></i> My Courses</h3>
            <p class="text-muted small">Semester <?php echo $selected_sem; ?> Subjects</p>
        </div>
        
        <form method="GET" class="d-flex align-items-center bg-white p-2 rounded shadow-sm">
            <label class="me-2 fw-bold text-secondary">Semester:</label>
            <select name="sem" class="form-select w-auto border-0 bg-light fw-bold" onchange="this.form.submit()">
                <?php
                // Show semesters 1 to 8
                for ($i = 1; $i <= 8; $i++) {
                    $sel = ($i == $selected_sem) ? "selected" : "";
                    echo "<option value='$i' $sel>Semester $i</option>";
                }
                ?>
            </select>
        </form>
    </div>

    <div class="row g-4">
        <?php
        // Fetch Courses + Teacher Name
        // Left Join Teachers through Allocations
        $sql = "SELECT Enrollments.*, Courses.course_title, Courses.credit_hours, Courses.course_type,
                Teachers.first_name as t_fname, Teachers.last_name as t_lname 
                FROM Enrollments 
                JOIN Courses ON Enrollments.course_code = Courses.course_code 
                LEFT JOIN Allocations ON Courses.course_code = Allocations.course_code 
                LEFT JOIN Teachers ON Allocations.employee_id = Teachers.employee_id
                WHERE Enrollments.roll_no = '$roll_no' AND Courses.semester = '$selected_sem'
                GROUP BY Courses.course_code"; // Avoid duplicates if multiple sections

        $res = $conn->query($sql);

        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $teacher_name = ($row['t_fname']) ? $row['t_fname']." ".$row['t_lname'] : "Not Assigned";
                $bg_badge = ($row['course_type'] == 'Lab') ? 'bg-warning text-dark' : 'bg-primary';

                echo '
                <div class="col-md-6 col-lg-4">
                    <div class="card course-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge '.$bg_badge.'">'.$row['course_type'].'</span>
                            <small class="text-muted fw-bold">'.$row['course_code'].'</small>
                        </div>
                        
                        <h5 class="fw-bold mb-2">'.$row['course_title'].'</h5>
                        
                        <div class="mb-3">
                            <span class="teacher-badge">
                                <i class="fa fa-chalkboard-teacher me-1"></i> '.$teacher_name.'
                            </span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                            <small class="text-muted"><i class="fa fa-clock me-1"></i> '.$row['credit_hours'].' Credit Hrs</small>
                            <span class="text-success small fw-bold"><i class="fa fa-check-circle me-1"></i> Enrolled</span>
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo '<div class="col-12"><div class="alert alert-warning text-center">No courses found for Semester '.$selected_sem.'</div></div>';
        }
        ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>