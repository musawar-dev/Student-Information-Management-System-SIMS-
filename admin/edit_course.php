<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'HOD') { header("Location: ../login.php"); exit(); }

$code = $_GET['code'];
// Fetch Data
$res = $conn->query("SELECT * FROM Courses WHERE course_code='$code'");
if($res->num_rows == 0) { die("Course Not Found"); }
$row = $res->fetch_assoc();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['course_title']);
    $credit = $_POST['credit_hours'];
    $type = $_POST['course_type'];
    $sem = $_POST['semester'];
    $batch = $_POST['batch_id'];

    $sql = "UPDATE Courses SET course_title='$title', credit_hours='$credit', course_type='$type', semester='$sem', batch_id='$batch' WHERE course_code='$code'";
    
    if($conn->query($sql)) {
        log_activity($_SESSION['user_id'], 'HOD', 'UPDATE', "Updated Course: $code");
        $message = "<div class='alert alert-success'>Course Updated! <a href='manage_courses.php'>Go Back</a></div>";
        $row = $conn->query("SELECT * FROM Courses WHERE course_code='$code'")->fetch_assoc();
    } else {
        $message = "<div class='alert alert-danger'>Error: ".$conn->error."</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background:#f8f9fa; padding:50px;}</style>
</head>
<body>
<div class="container" style="max-width: 600px;">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Edit Course: <?php echo $code; ?></h4>
        </div>
        <div class="card-body">
            <?php echo $message; ?>
            <form method="POST">
                <div class="mb-3">
                    <label>Course Title</label>
                    <input type="text" name="course_title" class="form-control" value="<?php echo $row['course_title']; ?>" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label>Credit Hours</label>
                        <select name="credit_hours" class="form-select">
                            <option <?php if($row['credit_hours']==3) echo 'selected'; ?>>3</option>
                            <option <?php if($row['credit_hours']==2) echo 'selected'; ?>>2</option>
                            <option <?php if($row['credit_hours']==1) echo 'selected'; ?>>1</option>
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label>Type</label>
                        <select name="course_type" class="form-select">
                            <option <?php if($row['course_type']=='Theory') echo 'selected'; ?>>Theory</option>
                            <option <?php if($row['course_type']=='Lab') echo 'selected'; ?>>Lab</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Semester</label>
                    <select name="semester" class="form-select">
                        <?php for($i=1; $i<=8; $i++){
                            $sel = ($row['semester'] == $i) ? 'selected' : '';
                            echo "<option value='$i' $sel>Semester $i</option>";
                        } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Batch</label>
                    <select name="batch_id" class="form-select">
                        <?php $b=$conn->query("SELECT * FROM Batches"); while($br=$b->fetch_assoc()){ 
                            $sel = ($br['batch_id'] == $row['batch_id']) ? "selected" : "";
                            echo "<option value='".$br['batch_id']."' $sel>".$br['batch_name']."</option>"; 
                        } ?>
                    </select>
                </div>
                <div class="text-end">
                    <a href="manage_courses.php" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-warning">Update Course</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>