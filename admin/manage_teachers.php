<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'HOD') { header("Location: ../login.php"); exit(); }

$message = "";

// --- DELETE LOGIC ---
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $t_check = $conn->query("SELECT first_name FROM Teachers WHERE user_id = '$id'");
    if($t_check->num_rows > 0) {
        $name = $t_check->fetch_assoc()['first_name'];
        $conn->query("DELETE FROM Teachers WHERE user_id = '$id'");
        $conn->query("DELETE FROM Users WHERE user_id = '$id'");
        // Allocations bhi delete karein
        $conn->query("DELETE FROM Allocations WHERE employee_id = (SELECT employee_id FROM Teachers WHERE user_id = '$id')");
        
        log_activity($_SESSION['user_id'], 'HOD', 'DELETE', "Deleted Teacher: $name");
        $message = "<div class='alert alert-success'>Teacher Deleted Successfully!</div>";
    }
}

// --- ADD TEACHER LOGIC ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitization
    $fname = $conn->real_escape_string($_POST['first_name']);
    $lname = $conn->real_escape_string($_POST['last_name']);
    $cnic = $conn->real_escape_string($_POST['cnic']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone_no']);
    $address = $conn->real_escape_string($_POST['address']);
    $qual = $_POST['qualification'];
    $desig = $_POST['designation'];
    $salary = $_POST['salary'];
    
    // Photo Handling (2MB Limit)
    $photo_name = "default_teacher.png";
    $save_data = true;

    if(isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0){
        if ($_FILES['profile_photo']['size'] > 2097152) { // 2MB Limit
            $message = "<div class='alert alert-danger'>Error: Image too big! Max 2MB allowed.</div>";
            $save_data = false;
        } else {
            $target_dir = "../uploads/";
            if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
            $ext = pathinfo($_FILES["profile_photo"]["name"], PATHINFO_EXTENSION);
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            
            if(in_array(strtolower($ext), $allowed)) {
                $photo_name = "teacher_" . time() . "." . $ext;
                move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_dir . $photo_name);
            } else {
                $message = "<div class='alert alert-danger'>Error: Only JPG, PNG, GIF allowed!</div>";
                $save_data = false;
            }
        }
    }

    if ($save_data) {
        // Duplicate Check
        $check = $conn->query("SELECT * FROM Users WHERE username = '$email'");
        if ($check->num_rows > 0) {
            $message = "<div class='alert alert-danger'>Error: Email/Username already exists!</div>";
        } else {
            // Insert User
            $conn->query("INSERT INTO Users (username, password, email, role) VALUES ('$email', 'teacher123', '$email', 'Teacher')");
            $user_id = $conn->insert_id;
            
            // Insert Teacher
            $sql = "INSERT INTO Teachers (user_id, first_name, last_name, cnic, qualification, designation, email, phone_no, address, salary, profile_photo) 
                    VALUES ('$user_id', '$fname', '$lname', '$cnic', '$qual', '$desig', '$email', '$phone', '$address', '$salary', '$photo_name')";
            
            if ($conn->query($sql)) {
                log_activity($_SESSION['user_id'], 'HOD', 'ADD', "Added Teacher: $fname $lname");
                $message = "<div class='alert alert-success'>Teacher Added Successfully!</div>";
            } else {
                $conn->query("DELETE FROM Users WHERE user_id = '$user_id'"); // Cleanup
                $message = "<div class='alert alert-danger'>DB Error: " . $conn->error . "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Teachers</title>
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
        .teacher-img { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; border: 2px solid #198754; }
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
        <a href="manage_teachers.php" class="sidebar-link active"><i class="fa fa-chalkboard-teacher me-2"></i> Teachers</a>
        <a href="manage_courses.php" class="sidebar-link"><i class="fa fa-book me-2"></i> Courses</a>
        <a href="assign_course.php" class="sidebar-link"><i class="fa fa-link me-2"></i> Assign Course</a>
        <a href="system_logs.php" class="sidebar-link"><i class="fa fa-history me-2"></i> System Logs</a>
    </div>
    <div class="logout-container">
        <a href="../logout.php" class="btn-logout"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Manage Teachers</h2>
        <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#addTeacherModal"><i class="fa fa-plus me-2"></i> Add New Teacher</button>
    </div>

    <?php echo $message; ?>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Profile</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Phone</th>
                        <th>Qualification</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM Teachers ORDER BY employee_id DESC";
                    $res = $conn->query($sql);
                    
                    if($res->num_rows > 0){
                        while($row = $res->fetch_assoc()){
                            $img = !empty($row['profile_photo']) ? "../uploads/".$row['profile_photo'] : "../uploads/default_teacher.png";
                            $img_tag = "<img src='$img' class='teacher-img' loading='lazy' onerror=\"this.src='https://via.placeholder.com/50'\">";
                            $desig = $row['designation'] ? $row['designation'] : '-';

                            echo "<tr>
                                <td class='ps-4'>$img_tag</td>
                                <td>
                                    <strong>".$row['first_name']." ".$row['last_name']."</strong><br>
                                    <small class='text-muted'>".$row['email']."</small>
                                </td>
                                <td><span class='badge bg-info text-dark'>$desig</span></td>
                                <td>".$row['phone_no']."</td>
                                <td>".$row['qualification']."</td>
                                <td class='text-end pe-4'>
                                    <button class='btn btn-sm btn-info text-white me-1' data-bs-toggle='modal' data-bs-target='#viewModal".$row['user_id']."'><i class='fa fa-eye'></i></button>
                                    <a href='edit_teacher.php?id=".$row['user_id']."' class='btn btn-sm btn-warning text-white me-1'><i class='fa fa-edit'></i></a>
                                    <a href='manage_teachers.php?delete_id=".$row['user_id']."' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this Teacher?\")'><i class='fa fa-trash'></i></a>
                                </td>
                            </tr>";

                            // VIEW MODAL
                            echo "
                            <div class='modal fade' id='viewModal".$row['user_id']."' tabindex='-1'>
                                <div class='modal-dialog'>
                                    <div class='modal-content'>
                                        <div class='modal-header bg-success text-white'>
                                            <h5 class='modal-title'>Teacher Details</h5>
                                            <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                        </div>
                                        <div class='modal-body text-center'>
                                            <img src='$img' class='rounded-circle mb-3 border border-3 border-success' width='120' height='120' style='object-fit:cover;'>
                                            <h4>".$row['first_name']." ".$row['last_name']."</h4>
                                            <p class='text-success fw-bold'>$desig</p>
                                            <hr>
                                            <div class='text-start row'>
                                                <div class='col-6 mb-2'><strong>Qualification:</strong><br>".$row['qualification']."</div>
                                                <div class='col-6 mb-2'><strong>Salary:</strong><br>".number_format($row['salary'])." PKR</div>
                                                <div class='col-6 mb-2'><strong>Phone:</strong><br>".$row['phone_no']."</div>
                                                <div class='col-6 mb-2'><strong>CNIC:</strong><br>".$row['cnic']."</div>
                                                <div class='col-12 mb-2'><strong>Email:</strong><br>".$row['email']."</div>
                                                <div class='col-12'><strong>Address:</strong><br>".$row['address']."</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                        }
                    } else { echo "<tr><td colspan='6' class='text-center p-4'>No Teachers Found</td></tr>"; }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addTeacherModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Add New Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6"><label>First Name *</label><input type="text" name="first_name" class="form-control" required></div>
                        <div class="col-md-6"><label>Last Name *</label><input type="text" name="last_name" class="form-control" required></div>
                        
                        <div class="col-md-6"><label>Email (Username) *</label><input type="email" name="email" class="form-control" required></div>
                        <div class="col-md-6"><label>Phone No</label><input type="text" name="phone_no" class="form-control" required></div>
                        
                        <div class="col-md-6"><label>CNIC</label><input type="text" name="cnic" class="form-control"></div>
                        <div class="col-md-6"><label>Salary</label><input type="number" name="salary" class="form-control"></div>
                        
                        <div class="col-md-6"><label>Qualification</label>
                            <select name="qualification" class="form-select">
                                <option>Bachelors</option><option>Masters</option><option>PhD</option>
                            </select>
                        </div>
                        <div class="col-md-6"><label>Designation</label>
                            <select name="designation" class="form-select">
                                <option>Lecturer</option><option>Assistant Professor</option><option>Professor</option>
                            </select>
                        </div>
                        
                        <div class="col-12"><label>Address</label><textarea name="address" class="form-control" rows="2"></textarea></div>
                        <div class="col-12">
                            <label class="text-success fw-bold">Profile Photo (Max 2MB)</label>
                            <input type="file" name="profile_photo" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save Teacher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>