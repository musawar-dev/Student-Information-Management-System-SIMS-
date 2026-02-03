<?php
session_start();
include '../db_connect.php';

// 1. Login Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Teacher') { 
    header("Location: ../login.php"); 
    exit(); 
}

$msg = ""; 

// --- FIX 1: SEC VARIABLE ERROR ---
// Agar URL me section nahi hai to default empty rakho taake error na aaye
$code = isset($_GET['code']) ? $_GET['code'] : '';
$sec = isset($_GET['sec']) ? $_GET['sec'] : ''; 
// ---------------------------------

// ====================================================
// 🔥 MAIN FIX: BATCH ID KO ALLOCATIONS SE NIKALNA 🔥
// ====================================================

// Step A: Logged in User ki 'Employee ID' (EMP-XX) nikalo
$user_id = $_SESSION['user_id'];
$teacher_query = $conn->query("SELECT employee_id FROM Teachers WHERE user_id = '$user_id'");
$teacher_data = $teacher_query->fetch_assoc();
$emp_id = $teacher_data['employee_id']; 

// Step B: Ab check karo ke dashboard se Batch ID aayi hai ya nahi?
if (isset($_GET['batch_id']) && !empty($_GET['batch_id'])) {
    $batch_id = $_GET['batch_id'];
} else {
    // Fallback: Agar URL mein batch nahi tha, to Database se dhoondo
    $alloc_sql = "SELECT batch_id FROM Allocations WHERE employee_id = '$emp_id' AND course_code = '$code'";
    $alloc_result = $conn->query($alloc_sql);
    
    if($alloc_result->num_rows > 0){
        $batch_row = $alloc_result->fetch_assoc();
        $batch_id = $batch_row['batch_id'];
    } else {
        $batch_id = 0; 
    }
}
// ====================================================


// 2. UPDATE MARKS LOGIC
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['sessional'] as $roll => $sess_mark) {
        $mid_mark = $_POST['mid'][$roll];
        $final_mark = $_POST['final'][$roll];
        
        // Marks update query
        $sql = "UPDATE Enrollments SET sessional_marks='$sess_mark', mid_marks='$mid_mark', final_marks='$final_mark' 
                WHERE roll_no='$roll' AND course_code='$code'";
        $conn->query($sql);
    }
    $msg = "<div class='alert alert-success'>Marks Updated Successfully!</div>";
}

// 3. AUTO-ENROLL STUDENTS
if($batch_id != 0) {
    $students = $conn->query("SELECT roll_no FROM Students WHERE batch_id = '$batch_id'");
    while($s = $students->fetch_assoc()){
        $r = $s['roll_no'];
        $chk = $conn->query("SELECT * FROM Enrollments WHERE roll_no='$r' AND course_code='$code'");
        if($chk->num_rows == 0){
            $conn->query("INSERT INTO Enrollments (roll_no, course_code) VALUES ('$r', '$code')");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Marks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        
        /* FIX 2: Sidebar hata diya aur layout Full Width kar diya */
        .container-custom {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        input[type=number] { width: 80px; text-align: center; }
        
        .header-area {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container-custom">
    
    <div class="header-area d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-1"><i class="fa fa-edit text-primary me-2"></i> Manage Marks</h3>
            <p class="text-muted mb-0">Course: <strong><?php echo $code; ?></strong> <?php if(!empty($sec)) { echo "| Section: $sec"; } ?></p>
        </div>
        <a href="my_courses.php" class="btn btn-outline-secondary"><i class="fa fa-arrow-left me-2"></i> Back to Courses</a>
    </div>
    
    <?php echo $msg; ?>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">
            <form method="POST">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>Roll No</th>
                                <th>Name</th>
                                <th>Sessional (20)</th>
                                <th>Mid (30)</th>
                                <th>Final (50)</th>
                                <th>Total (100)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch Students with Marks
                            $sql = "SELECT Enrollments.*, Students.first_name, Students.surname 
                                    FROM Enrollments 
                                    JOIN Students ON Enrollments.roll_no = Students.roll_no 
                                    WHERE Enrollments.course_code = '$code' 
                                    ORDER BY Students.roll_no ASC";
                            $res = $conn->query($sql);

                            if($res->num_rows > 0){
                                while($row = $res->fetch_assoc()){
                                    $total = $row['sessional_marks'] + $row['mid_marks'] + $row['final_marks'];
                                    $color = ($total >= 50) ? 'text-success' : 'text-danger';

                                    echo "<tr>
                                        <td class='text-center fw-bold'>".$row['roll_no']."</td>
                                        <td>".$row['first_name']." ".$row['surname']."</td>
                                        
                                        <td class='text-center'><input type='number' name='sessional[".$row['roll_no']."]' value='".$row['sessional_marks']."' min='0' max='20' class='form-control mx-auto'></td>
                                        
                                        <td class='text-center'><input type='number' name='mid[".$row['roll_no']."]' value='".$row['mid_marks']."' min='0' max='30' class='form-control mx-auto'></td>
                                        
                                        <td class='text-center'><input type='number' name='final[".$row['roll_no']."]' value='".$row['final_marks']."' min='0' max='50' class='form-control mx-auto'></td>
                                        
                                        <td class='text-center fw-bold $color'>$total</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center py-4 text-muted'>No Students found in this Batch.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-5"><i class="fa fa-save me-2"></i> Save Result</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>