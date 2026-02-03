<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'HOD') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
// Users table join kiya taake Email mil saky
$row = $conn->query("SELECT Students.*, Users.email FROM Students JOIN Users ON Students.user_id = Users.user_id WHERE Students.user_id='$id'")->fetch_assoc();
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $conn->real_escape_string($_POST['first_name']);
    $sname = $conn->real_escape_string($_POST['surname']);
    $father = $conn->real_escape_string($_POST['father_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $cnic = $conn->real_escape_string($_POST['cnic']);
    $address = $conn->real_escape_string($_POST['address']);
    $dob = $_POST['dob'] ? "'" . $_POST['dob'] . "'" : "NULL";
    $gender = $_POST['gender'];
    $batch = $_POST['batch_id'];
    // SQL Update chalne se pehle ya baad mein ye line add karein:
    $conn->query("UPDATE Users SET email='$email', username='$email' WHERE user_id='$id'");

    // Photo Update Logic
    $photo_sql_part = "";
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $target_dir = "../uploads/";
        $ext = pathinfo($_FILES["profile_photo"]["name"], PATHINFO_EXTENSION);
        $new_name = $row['roll_no'] . "_updated." . $ext;
        move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_dir . $new_name);
        $photo_sql_part = ", profile_photo = '$new_name'";
    }

    $sql = "UPDATE Students SET first_name='$fname', surname='$sname', father_name='$father', cnic='$cnic', dob=$dob, gender='$gender', address='$address', batch_id='$batch' $photo_sql_part WHERE user_id='$id'";

    if ($conn->query($sql)) {
        log_activity($_SESSION['user_id'], 'HOD', 'UPDATE', "Updated Student: " . $row['roll_no']);
        $msg = "<div class='alert alert-success'>Student Updated! <a href='manage_students.php'>Go Back</a></div>";
        // Refresh data
        $row = $conn->query("SELECT * FROM Students WHERE user_id='$id'")->fetch_assoc();
    } else {
        $msg = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            padding: 30px;
        }
    </style>
</head>

<body>
    <div class="container" style="max-width: 800px;">
        <div class="card shadow-lg">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">Edit Student: <?php echo $row['roll_no']; ?></h4>
            </div>
            <div class="card-body">
                <?php echo $msg; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="text-center mb-4">
                        <?php $img = !empty($row['profile_photo']) ? "../uploads/" . $row['profile_photo'] : "../uploads/default_student.png"; ?>
                        <img src="<?php echo $img; ?>" class="rounded-circle border border-warning" width="100"
                            height="100" style="object-fit:cover;">
                        <br><label class="mt-2 text-primary" style="cursor:pointer;">Change Photo <input type="file"
                                name="profile_photo" style="display:none;"></label>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6"><label>First Name</label><input type="text" name="first_name"
                                class="form-control" value="<?php echo $row['first_name']; ?>" required></div>
                        <div class="col-md-6"><label>Surname</label><input type="text" name="surname"
                                class="form-control" value="<?php echo $row['surname']; ?>" required></div>
                        <div class="col-md-6"><label>Father Name</label><input type="text" name="father_name"
                                class="form-control" value="<?php echo $row['father_name']; ?>"></div>
                        <div class="col-md-6"><label>Email (Login Username)</label><input type="text" name="email"
                                class="form-control" value="<?php echo $row['email']; ?>" required></div>
                        <div class="col-md-6"><label>CNIC Number</label>
                            <input type="text" name="cnic" id="cnic" class="form-control" placeholder="41300-1234567-1"
                                maxlength="15" required>
                            <script>document.getElementById('cnic').addEventListener('input', function (e) {
                                    var x = e.target.value.replace(/\D/g, '').match(/(\d{0,5})(\d{0,7})(\d{0,1})/);
                                    e.target.value = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '');
                                });</script>
                        </div>
                        <div class="col-md-6"><label>DOB</label><input type="date" name="dob" class="form-control"
                                value="<?php echo $row['dob']; ?>"></div>
                        <div class="col-md-6"><label>Gender</label>
                            <select name="gender" class="form-select">
                                <option <?php if ($row['gender'] == 'Male')
                                    echo 'selected'; ?>>Male</option>
                                <option <?php if ($row['gender'] == 'Female')
                                    echo 'selected'; ?>>Female</option>
                            </select>
                        </div>
                        <div class="col-md-12"><label>Address</label><textarea name="address"
                                class="form-control"><?php echo $row['address']; ?></textarea></div>
                        <div class="col-md-12"><label>Batch</label>
                            <select name="batch_id" class="form-select">
                                <?php $b = $conn->query("SELECT * FROM Batches");
                                while ($br = $b->fetch_assoc()) {
                                    $sel = ($br['batch_id'] == $row['batch_id']) ? "selected" : "";
                                    echo "<option value='" . $br['batch_id'] . "' $sel>" . $br['batch_name'] . "</option>";
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <a href="manage_students.php" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-warning">Update Details</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>