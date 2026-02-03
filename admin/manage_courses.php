<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'HOD') { header("Location: ../login.php"); exit(); }

$message = "";

// --- DELETE COURSE ---
if (isset($_GET['delete_code'])) {
    $code = $_GET['delete_code'];
    // Pehle Allocations delete karen taake foreign key error na aaye
    $conn->query("DELETE FROM Allocations WHERE course_code = '$code'");
    $conn->query("DELETE FROM Enrollments WHERE course_code = '$code'");
    
    if ($conn->query("DELETE FROM Courses WHERE course_code = '$code'")) {
        log_activity($_SESSION['user_id'], 'HOD', 'DELETE', "Deleted Course: $code");
        $message = "<div class='alert alert-success'>Course Deleted Successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// --- ADD COURSE ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $conn->real_escape_string($_POST['course_code']);
    $title = $conn->real_escape_string($_POST['course_title']);
    $credit = $_POST['credit_hours'];
    $sem = $_POST['semester'];
    $type = $_POST['course_type'];

    $check = $conn->query("SELECT * FROM Courses WHERE course_code = '$code'");
    if ($check->num_rows > 0) {
        $message = "<div class='alert alert-danger'>Course Code already exists!</div>";
    } else {
        // Logic Updated: Batch ID remove kar diya insert query se
        $sql = "INSERT INTO Courses (course_code, course_title, credit_hours, semester, course_type) 
                VALUES ('$code', '$title', '$credit', '$sem', '$type')";
        
        if ($conn->query($sql)) {
            log_activity($_SESSION['user_id'], 'HOD', 'ADD', "Added Course: $code");
            $message = "<div class='alert alert-success'>Course Added Successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }
}

// --- FETCH COURSES (Main Fix for Fatal Error) ---
// Humne JOIN hata diya hai kyu ke Batch ID ab Courses table me nahi hai.
$sql = "SELECT * FROM Courses ORDER BY semester ASC, course_code ASC";
$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Courses</title>
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
        <a href="manage_courses.php" class="sidebar-link active"><i class="fa fa-book me-2"></i> Courses</a>
        <a href="assign_course.php" class="sidebar-link"><i class="fa fa-link me-2"></i> Assign Course</a>
        <a href="system_logs.php" class="sidebar-link"><i class="fa fa-history me-2"></i> System Logs</a>
    </div>
    <div class="logout-container">
        <a href="../logout.php" class="btn-logout"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Manage Courses</h2>
        <button class="btn btn-warning text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addCourseModal"><i class="fa fa-plus me-2"></i> Add New Course</button>
    </div>

    <?php echo $message; ?>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Code</th>
                        <th>Title</th>
                        <th>Sem</th>
                        <th>C.H</th>
                        <th>Type</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($res && $res->num_rows > 0){
                        while($row = $res->fetch_assoc()){
                            $type_badge = ($row['course_type'] == 'Theory') ? 'bg-primary' : 'bg-success';
                            
                            echo "<tr>
                                <td class='ps-4 fw-bold'>".$row['course_code']."</td>
                                <td>".$row['course_title']."</td>
                                <td>".$row['semester']."</td>
                                <td>".$row['credit_hours']."</td>
                                <td><span class='badge $type_badge'>".$row['course_type']."</span></td>
                                <td class='text-end pe-4'>
                                    <a href='edit_course.php?code=".$row['course_code']."' class='btn btn-sm btn-warning text-white me-1'><i class='fa fa-edit'></i></a>
                                    <a href='manage_courses.php?delete_code=".$row['course_code']."' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this Course? All allocations will be removed.\")'><i class='fa fa-trash'></i></a>
                                </td>
                            </tr>";
                        }
                    } else { echo "<tr><td colspan='6' class='text-center p-4'>No Courses Found</td></tr>"; }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Add New Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Course Code (Unique)</label>
                        <input type="text" name="course_code" class="form-control" placeholder="e.g. SWE-312" required>
                    </div>
                    <div class="mb-3">
                        <label>Course Title</label>
                        <input type="text" name="course_title" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label>Credit Hours</label>
                            <select name="credit_hours" class="form-select">
                                <option>3</option><option>2</option><option>1</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label>Type</label>
                            <select name="course_type" class="form-select">
                                <option>Theory</option><option>Lab</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Target Semester</label>
                        <select name="semester" class="form-select">
                            <?php for($i=1; $i<=8; $i++) echo "<option value='$i'>Semester $i</option>"; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Add Course</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>