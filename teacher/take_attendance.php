<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Teacher') { 
    header("Location: ../login.php"); 
    exit(); 
}

$code = $_GET['code'];
$msg = "";

// ====================================================
// 🔥 FIX: BATCH ID LOGIC (Smart Fetch) 🔥
// ====================================================
$user_id = $_SESSION['user_id'];

// 1. Teacher ID nikalo
$t_res = $conn->query("SELECT employee_id FROM Teachers WHERE user_id = '$user_id'");
$t_row = $t_res->fetch_assoc();
$emp_id = $t_row['employee_id'];

// 2. Batch ID nikalo (URL se ya Database se)
if (isset($_GET['batch_id']) && !empty($_GET['batch_id'])) {
    $batch_id = $_GET['batch_id'];
} else {
    // Allocations table se dhoondo
    $alloc_res = $conn->query("SELECT batch_id FROM Allocations WHERE employee_id = '$emp_id' AND course_code = '$code'");
    if($alloc_res->num_rows > 0){
        $b_row = $alloc_res->fetch_assoc();
        $batch_id = $b_row['batch_id'];
    } else {
        $batch_id = 0;
    }
}
// ====================================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!empty($_POST['present_students'])) {
        foreach($_POST['present_students'] as $roll_no) {
            $conn->query("UPDATE Enrollments SET classes_attended = classes_attended + 1 WHERE roll_no = '$roll_no' AND course_code = '$code'");
        }
        $msg = "<div class='alert alert-success'>Attendance Saved Successfully!</div>";
    }
}

// Ensure students exist in Enrollments
if($batch_id != 0){
    $students = $conn->query("SELECT roll_no FROM Students WHERE batch_id = '$batch_id'");
    while($s = $students->fetch_assoc()){
        $r = $s['roll_no'];
        $chk = $conn->query("SELECT * FROM Enrollments WHERE roll_no='$r' AND course_code='$code'");
        if($chk->num_rows == 0){
            $conn->query("INSERT INTO Enrollments (roll_no, course_code, classes_attended) VALUES ('$r', '$code', 0)");
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Take Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="container bg-white p-4 rounded shadow">
        <h3>Take Attendance: <?php echo $code; ?></h3>
        <a href="my_courses.php" class="btn btn-secondary mb-3">Back</a>
        <?php echo $msg; ?>
        <form method="POST">
            <table class="table table-bordered">
                <tr><th>Student</th><th>Total Presents</th><th>Mark Present</th></tr>
                <?php
                $q = "SELECT Enrollments.*, Students.first_name, Students.surname FROM Enrollments JOIN Students ON Enrollments.roll_no = Students.roll_no WHERE Enrollments.course_code = '$code'";
                $res = $conn->query($q);
                while($row = $res->fetch_assoc()){
                    echo "<tr>
                        <td>".$row['first_name']." ".$row['surname']." (".$row['roll_no'].")</td>
                        <td>".$row['classes_attended']."</td>
                        <td class='text-center'><input type='checkbox' name='present_students[]' value='".$row['roll_no']."' style='transform: scale(1.5);'></td>
                    </tr>";
                }
                ?>
            </table>
            <button class="btn btn-success w-100">Save Attendance</button>
        </form>
    </div>
</body>
</html>