<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'HOD') { header("Location: ../login.php"); exit(); }

$id = $_GET['id'];
$msg = "";

// 1. Fetch Data
$res = $conn->query("SELECT * FROM Teachers WHERE user_id='$id'");
if($res->num_rows == 0) {
    die("<div class='container mt-5 alert alert-danger text-center'>Error: Teacher ID not found. <a href='manage_teachers.php'>Go Back</a></div>");
}
$row = $res->fetch_assoc();

// 2. Update Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $conn->real_escape_string($_POST['first_name']);
    $lname = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']); // New Email Field
    $phone = $conn->real_escape_string($_POST['phone_no']);
    $address = $conn->real_escape_string($_POST['address']);
    $cnic = $conn->real_escape_string($_POST['cnic']);
    $qual = $_POST['qualification'];
    $desig = $_POST['designation'];
    $salary = $_POST['salary'];
    // SQL Update chalne se pehle ya baad mein ye line add karein:
    $conn->query("UPDATE Users SET email='$email', username='$email' WHERE user_id='$id'");

    // Photo Logic (Ab ye tab chalega jab button dabaya jayega)
    $photo_sql_part = "";
    if(isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0){
        $target_dir = "../uploads/";
        $ext = pathinfo($_FILES["profile_photo"]["name"], PATHINFO_EXTENSION);
        $new_name = "teacher_" . time() . "." . $ext;
        move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_dir . $new_name);
        $photo_sql_part = ", profile_photo = '$new_name'";
    }

    // Update Teachers Table
    $sql = "UPDATE Teachers SET 
            first_name='$fname', 
            last_name='$lname', 
            email='$email', 
            phone_no='$phone', 
            address='$address', 
            cnic='$cnic', 
            qualification='$qual', 
            designation='$desig', 
            salary='$salary' 
            $photo_sql_part 
            WHERE user_id='$id'";
    
    if($conn->query($sql)) {
        // Update Users Table (Taake Login bhi naye email se ho)
        $conn->query("UPDATE Users SET username='$email', email='$email' WHERE user_id='$id'");

        log_activity($_SESSION['user_id'], 'HOD', 'UPDATE', "Updated Teacher: $fname $lname");
        $msg = "<div class='alert alert-success'>Teacher Details Updated Successfully! <a href='manage_teachers.php'>Go Back</a></div>";
        $row = $conn->query("SELECT * FROM Teachers WHERE user_id='$id'")->fetch_assoc();
    } else {
        $msg = "<div class='alert alert-danger'>Error: ".$conn->error."</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body{background:#f8f9fa; padding:30px;}</style>
</head>
<body>
<div class="container" style="max-width: 800px;">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="fa fa-edit me-2"></i> Edit Teacher</h4>
        </div>
        <div class="card-body">
            <?php echo $msg; ?>
            
            <form method="POST" enctype="multipart/form-data">
                
                <div class="text-center mb-4">
                    <?php $img = !empty($row['profile_photo']) ? "../uploads/".$row['profile_photo'] : "../uploads/default_teacher.png"; ?>
                    <img src="<?php echo $img; ?>" class="rounded-circle border border-3 border-success" width="120" height="120" style="object-fit:cover;">
                    <br>
                    <label class="btn btn-sm btn-outline-success mt-2 rounded-pill">
                        <i class="fa fa-camera"></i> Change Photo
                        <input type="file" name="profile_photo" style="display:none;"> 
                    </label>
                    <div class="text-muted small mt-1">Click "Update Details" to save new photo</div>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="fw-bold">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="<?php echo $row['first_name']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="<?php echo $row['last_name']; ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-bold">Email (Login Username)</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="fw-bold">Phone No</label>
                        <input type="text" name="phone_no" class="form-control" value="<?php echo $row['phone_no']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">CNIC</label>
                        <input type="text" name="cnic" class="form-control" value="<?php echo $row['cnic']; ?>">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="fw-bold">Qualification</label>
                        <select name="qualification" class="form-select">
                            <option <?php if($row['qualification']=='Bachelors') echo 'selected'; ?>>Bachelors</option>
                            <option <?php if($row['qualification']=='Masters') echo 'selected'; ?>>Masters</option>
                            <option <?php if($row['qualification']=='PhD') echo 'selected'; ?>>PhD</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Designation</label>
                        <select name="designation" class="form-select">
                            <option <?php if($row['designation']=='Lecturer') echo 'selected'; ?>>Lecturer</option>
                            <option <?php if($row['designation']=='Assistant Professor') echo 'selected'; ?>>Assistant Professor</option>
                            <option <?php if($row['designation']=='Professor') echo 'selected'; ?>>Professor</option>
                        </select>
                    </div>
                    
                    <div class="col-md-12">
                        <label class="fw-bold">Address</label>
                        <textarea name="address" class="form-control" rows="2"><?php echo $row['address']; ?></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="fw-bold">Salary (PKR)</label>
                        <input type="number" name="salary" class="form-control" 
                               placeholder="e.g. 50000"
                               value="<?php echo ($row['salary'] > 0) ? $row['salary'] : ''; ?>">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="manage_teachers.php" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-success px-4">Update Details</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>