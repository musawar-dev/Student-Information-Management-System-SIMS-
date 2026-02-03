<?php
session_start();
include '../db_connect.php';

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'HOD') { 
    header("Location: ../login.php"); 
    exit(); 
}

$message = "";

// --- 1. HANDLE MESSAGES (REDIRECT KE BAAD) ---
if(isset($_GET['msg'])){
    if($_GET['msg'] == 'assigned'){
        $message = "<div class='alert alert-success'>Course Assigned Successfully!</div>";
    }
    elseif($_GET['msg'] == 'deleted'){
        $message = "<div class='alert alert-success'>Allocation Removed Successfully!</div>";
    }
    elseif($_GET['msg'] == 'exists'){
        $message = "<div class='alert alert-danger'>Error: This Course is already assigned to this Batch & Section!</div>";
    }
    elseif($_GET['msg'] == 'error'){
        $message = "<div class='alert alert-danger'>Database Error Occurred!</div>";
    }
}

// --- 2. ASSIGN COURSE LOGIC ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assign_course'])) {
    $teacher_id = $_POST['teacher_id'];
    $batch_id = $_POST['batch_id'];
    $course_code = $_POST['course_code'];
    $section = $_POST['section'];

    // Duplicate Check
    $check = $conn->query("SELECT * FROM Allocations WHERE course_code='$course_code' AND section='$section' AND batch_id='$batch_id'");

    if ($check->num_rows > 0) {
        // Redirect with Error
        header("Location: assign_course.php?msg=exists");
        exit();
    } else {
        $sql = "INSERT INTO Allocations (employee_id, course_code, section, batch_id) 
                VALUES ('$teacher_id', '$course_code', '$section', '$batch_id')";
        
        if ($conn->query($sql)) {
            log_activity($_SESSION['user_id'], 'HOD', 'ASSIGN', "Assigned $course_code to Batch $batch_id");
            // Redirect with Success
            header("Location: assign_course.php?msg=assigned");
            exit();
        } else {
            header("Location: assign_course.php?msg=error");
            exit();
        }
    }
}

// --- 3. DELETE ALLOCATION ---
if (isset($_GET['del_id'])) {
    $del_id = $_GET['del_id'];
    if($conn->query("DELETE FROM Allocations WHERE allocation_id='$del_id'")){
        // Redirect after Delete
        header("Location: assign_course.php?msg=deleted");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Assign Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* SIDEBAR CSS */
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
        <img src="../uploads/logo.png" alt="Logo" style="width: 60px; height: 60px; background: white; border-radius: 50%; padding: 5px; margin-bottom: 10px;">
        <h4 class="mb-0">HOD Panel</h4>
    </div>
    <div class="sidebar-menu">
        <a href="dashboard.php" class="sidebar-link"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="manage_students.php" class="sidebar-link"><i class="fa fa-user-graduate me-2"></i> Students</a>
        <a href="manage_teachers.php" class="sidebar-link"><i class="fa fa-chalkboard-teacher me-2"></i> Teachers</a>
        <a href="manage_courses.php" class="sidebar-link"><i class="fa fa-book me-2"></i> Courses</a>
        <a href="assign_course.php" class="sidebar-link active"><i class="fa fa-link me-2"></i> Assign Course</a>
        <a href="system_logs.php" class="sidebar-link"><i class="fa fa-history me-2"></i> System Logs</a>
    </div>
    <div class="logout-container">
        <a href="../logout.php" class="btn-logout"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <h2 class="fw-bold text-dark mb-4">Assign Course to Teacher</h2>
    <?php echo $message; ?>

    <div class="card shadow-sm border-0 rounded-4 mb-5">
        <div class="card-header bg-warning text-dark fw-bold">
            <i class="fa fa-link me-2"></i> New Allocation
        </div>
        <div class="card-body p-4">
            <form method="POST">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Select Teacher</label>
                        <select name="teacher_id" class="form-select" required>
                            <option value="">-- Choose Teacher --</option>
                            <?php 
                            $t = $conn->query("SELECT * FROM Teachers"); 
                            while($row = $t->fetch_assoc()){
                                $full_name = $row['first_name'] . " " . $row['last_name'];
                                echo "<option value='".$row['employee_id']."'>".$full_name."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Select Batch</label>
                        <select name="batch_id" class="form-select" required>
                            <option value="">-- Choose Batch --</option>
                            <?php 
                            $b = $conn->query("SELECT * FROM Batches ORDER BY batch_name DESC"); 
                            while($row = $b->fetch_assoc()){
                                echo "<option value='".$row['batch_id']."'>".$row['batch_name']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-bold">Select Course</label>
                        <select name="course_code" class="form-select" required>
                            <option value="">-- Choose Subject --</option>
                            <?php 
                            $c = $conn->query("SELECT * FROM Courses ORDER BY semester ASC"); 
                            while($row = $c->fetch_assoc()){
                                echo "<option value='".$row['course_code']."'>".$row['course_title']." (".$row['course_code'].") - Sem ".$row['semester']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Section</label>
                        <select name="section" class="form-select">
                            <option>A</option>
                            <option>B</option>
                            <option>C</option>
                            <option>Morning</option>
                            <option>Evening</option>
                        </select>
                    </div>
                </div>

                <button type="submit" name="assign_course" class="btn btn-dark w-100 mt-2"><i class="fa fa-check-circle me-2"></i> Assign Course</button>
            </form>
        </div>
    </div>

    <h4 class="fw-bold mb-3"><i class="fa fa-list me-2"></i> Active Allocations</h4>
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Teacher</th>
                        <th>Course</th>
                        <th>Batch</th>
                        <th>Sec</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query Updated: first_name, last_name, batch_name
                    $sql = "SELECT Allocations.*, Teachers.first_name, Teachers.last_name, Courses.course_title, Batches.batch_name 
                            FROM Allocations 
                            INNER JOIN Teachers ON Allocations.employee_id = Teachers.employee_id 
                            INNER JOIN Courses ON Allocations.course_code = Courses.course_code
                            INNER JOIN Batches ON Allocations.batch_id = Batches.batch_id
                            ORDER BY allocation_id DESC";
                    
                    $res = $conn->query($sql);

                    if($res && $res->num_rows > 0){
                        while($row = $res->fetch_assoc()){
                            $t_name = $row['first_name'] . " " . $row['last_name'];
                            
                            echo "<tr>
                                <td class='ps-4 fw-bold'>".$t_name."</td>
                                <td>".$row['course_title']." <small class='text-muted d-block'>".$row['course_code']."</small></td>
                                <td><span class='badge bg-info text-dark'>".$row['batch_name']."</span></td>
                                <td>".$row['section']."</td>
                                <td class='text-end pe-4'>
                                    <a href='assign_course.php?del_id=".$row['allocation_id']."' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Remove this allocation?\")'><i class='fa fa-trash'></i> Remove</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center p-4 text-muted'>No allocations found.</td></tr>";
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