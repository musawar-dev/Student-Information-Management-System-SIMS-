<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'HOD') {
    header("Location: ../login.php");
    exit();
}

$message = "";

// --- DELETE LOGIC ---
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $s_check = $conn->query("SELECT roll_no FROM Students WHERE user_id = '$id'");
    if ($s_check->num_rows > 0) {
        $roll = $s_check->fetch_assoc()['roll_no'];
        $conn->query("DELETE FROM Students WHERE user_id = '$id'");
        $conn->query("DELETE FROM Users WHERE user_id = '$id'");
        $conn->query("DELETE FROM Enrollments WHERE roll_no = '$roll'"); // Cleanup Marks

        log_activity($_SESSION['user_id'], 'HOD', 'DELETE', "Deleted Student: $roll");
        $message = "<div class='alert alert-success'>Student Deleted Successfully!</div>";
    }
}

// --- ADD STUDENT LOGIC ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitization
    $roll = $conn->real_escape_string($_POST['roll_no']);
    $fname = $conn->real_escape_string($_POST['first_name']);
    $sname = $conn->real_escape_string($_POST['surname']);
    $father = $conn->real_escape_string($_POST['father_name']);
    $cnic = $conn->real_escape_string($_POST['cnic']);
    $address = $conn->real_escape_string($_POST['address']);
    $dob = $_POST['dob'] ? "'" . $_POST['dob'] . "'" : "NULL";
    $gender = $_POST['gender'];
    $batch = $_POST['batch_id'];
    $dept = 1;

    // Photo Handling
    $photo_name = "default_student.png";
    $save_data = true;

    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        if ($_FILES['profile_photo']['size'] > 2097152) { // 2MB Check
            $message = "<div class='alert alert-danger'>Error: Image too big! Max 2MB allowed.</div>";
            $save_data = false;
        } else {
            $target_dir = "../uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $ext = pathinfo($_FILES["profile_photo"]["name"], PATHINFO_EXTENSION);
            $allowed = array('jpg', 'jpeg', 'png', 'gif');

            if (in_array(strtolower($ext), $allowed)) {
                $photo_name = $roll . "." . $ext;
                move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_dir . $photo_name);
            } else {
                $message = "<div class='alert alert-danger'>Error: Only JPG, PNG, GIF allowed!</div>";
                $save_data = false;
            }
        }
    }

    if ($save_data) {
        $check = $conn->query("SELECT * FROM Users WHERE username = '$roll'");
        if ($check->num_rows > 0) {
            $message = "<div class='alert alert-danger'>Roll No already exists!</div>";
        } else {
            // User Insert
            $conn->query("INSERT INTO Users (username, password, email, role) VALUES ('$roll', 'student123', '$roll', 'Student')");
            $user_id = $conn->insert_id;

            // Student Insert (Note: No 'email' column here)
            $sql = "INSERT INTO Students (user_id, roll_no, first_name, surname, father_name, cnic, dob, gender, address, profile_photo, batch_id, dept_id) 
                    VALUES ('$user_id', '$roll', '$fname', '$sname', '$father', '$cnic', $dob, '$gender', '$address', '$photo_name', '$batch', '$dept')";

            if ($conn->query($sql)) {
                log_activity($_SESSION['user_id'], 'HOD', 'ADD', "Added Student: $roll");
                $message = "<div class='alert alert-success'>Student Added Successfully!</div>";
            } else {
                $conn->query("DELETE FROM Users WHERE user_id = '$user_id'"); // Cleanup User if Student fails
                $message = "<div class='alert alert-danger'>DB Error: " . $conn->error . "</div>";
            }
        }
    }
}

// --- DOB CALCULATION (18 Years Ago) ---
$max_date = date('Y-m-d', strtotime('-18 years'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* PRO SIDEBAR CSS */
        .sidebar {
            height: 100vh;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #212529 0%, #343a40 100%);
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu {
            flex-grow: 1;
            overflow-y: auto;
            padding-top: 10px;
        }

        .sidebar-link {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 16px;
            color: #adb5bd;
            display: block;
            border-left: 4px solid transparent;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background-color: #495057;
            color: white;
            border-left: 4px solid #0d6efd;
        }

        .logout-container {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-logout {
            background: rgba(220, 53, 69, 0.1);
            color: #ff6b6b;
            border: 1px solid #dc3545;
            display: block;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-logout:hover {
            background: #dc3545;
            color: white;
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
        }

        .student-img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #0d6efd;
        }

        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../uploads/logo.png" alt="University Logo"
                style="width: 60px; height: 60px; object-fit: contain; background: white; border-radius: 50%; padding: 5px; margin-bottom: 10px;">

            <h4 class="mb-0"><i class="fa fa-user-shield me-2"></i> HOD Panel</h4>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard.php" class="sidebar-link"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="manage_students.php" class="sidebar-link active"><i class="fa fa-user-graduate me-2"></i>
                Students</a>
            <a href="manage_teachers.php" class="sidebar-link"><i class="fa fa-chalkboard-teacher me-2"></i>
                Teachers</a>
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
            <h2 class="fw-bold text-dark">Manage Students</h2>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal"><i
                    class="fa fa-plus me-2"></i> Add New Student</button>
        </div>

        <?php echo $message; ?>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Photo</th>
                            <th>Roll No</th>
                            <th>Name</th>
                            <th>Batch</th>
                            <th>Academic Progress</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT Students.*, Batches.batch_name FROM Students LEFT JOIN Batches ON Students.batch_id = Batches.batch_id ORDER BY roll_no ASC";
                        $res = $conn->query($sql);

                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $img = !empty($row['profile_photo']) ? "../uploads/" . $row['profile_photo'] : "../uploads/default_student.png";
                                $img_tag = "<img src='$img' class='student-img' loading='lazy' onerror=\"this.src='https://via.placeholder.com/50'\">";

                                // REAL PROGRESS LOGIC
                                $roll_check = $row['roll_no'];
                                $prog_q = $conn->query("SELECT SUM(sessional_marks + mid_marks + final_marks) as obt, COUNT(*) as courses FROM Enrollments WHERE roll_no = '$roll_check'");
                                $prog_data = $prog_q->fetch_assoc();

                                $percentage = 0;
                                if ($prog_data['courses'] > 0) {
                                    $total_max = $prog_data['courses'] * 100;
                                    $total_obt = $prog_data['obt'];
                                    $percentage = ($total_obt / $total_max) * 100;
                                }
                                $percentage = round($percentage);
                                $prog_color = ($percentage < 50) ? 'bg-danger' : (($percentage < 70) ? 'bg-warning' : 'bg-success');

                                echo "<tr>
                                <td class='ps-4'>$img_tag</td>
                                <td><strong>" . $row['roll_no'] . "</strong></td>
                                <td>" . $row['first_name'] . " " . $row['surname'] . "</td>
                                <td><span class='badge bg-secondary'>" . $row['batch_name'] . "</span></td>
                                <td style='width: 20%'>
                                    <div class='d-flex align-items-center'>
                                        <span class='me-2 small fw-bold'>$percentage%</span>
                                        <div class='progress flex-grow-1'>
                                            <div class='progress-bar $prog_color' style='width: $percentage%'></div>
                                        </div>
                                    </div>
                                    <small class='text-muted' style='font-size:10px;'>Based on Result</small>
                                </td>
                                <td class='text-end pe-4'>
                                    <button class='btn btn-sm btn-info text-white me-1' data-bs-toggle='modal' data-bs-target='#viewModal" . $row['user_id'] . "'><i class='fa fa-eye'></i></button>
                                    <a href='edit_student.php?id=" . $row['user_id'] . "' class='btn btn-sm btn-warning text-white me-1'><i class='fa fa-edit'></i></a>
                                    <a href='manage_students.php?delete_id=" . $row['user_id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this student?\")'><i class='fa fa-trash'></i></a>
                                </td>
                            </tr>";

                                // VIEW MODAL (Same as before)
                                $dob_view = $row['dob'] ? date("d M Y", strtotime($row['dob'])) : 'N/A';
                                echo "
                            <div class='modal fade' id='viewModal" . $row['user_id'] . "' tabindex='-1'>
                                <div class='modal-dialog'>
                                    <div class='modal-content'>
                                        <div class='modal-header bg-info text-white'>
                                            <h5 class='modal-title'>Student Details</h5>
                                            <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                        </div>
                                        <div class='modal-body text-center'>
                                            <img src='$img' class='rounded-circle mb-3 border border-3 border-info' width='120' height='120' style='object-fit:cover;'>
                                            <h4>" . $row['first_name'] . " " . $row['surname'] . "</h4>
                                            <p class='text-muted'>" . $row['roll_no'] . "</p>
                                            <h3 class='text-info'>$percentage% <small class='fs-6 text-muted'>Score</small></h3>
                                            <hr>
                                            <div class='text-start row'>
                                                <div class='col-6 mb-2'><strong>Father Name:</strong><br>" . $row['father_name'] . "</div>
                                                <div class='col-6 mb-2'><strong>CNIC:</strong><br>" . $row['cnic'] . "</div>
                                                <div class='col-6 mb-2'><strong>DOB:</strong><br>$dob_view</div>
                                                <div class='col-6 mb-2'><strong>Gender:</strong><br>" . $row['gender'] . "</div>
                                                <div class='col-12'><strong>Address:</strong><br>" . $row['address'] . "</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center p-4'>No Students Found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addStudentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-6"><label>Roll No *</label><input type="text" name="roll_no"
                                    class="form-control" required></div>
                            <div class="col-md-6"><label>Batch</label>
                                <select name="batch_id" class="form-select">
                                    <?php $b = $conn->query("SELECT * FROM Batches");
                                    while ($row = $b->fetch_assoc()) {
                                        echo "<option value='" . $row['batch_id'] . "'>" . $row['batch_name'] . "</option>";
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-6"><label>First Name *</label><input type="text" name="first_name"
                                    class="form-control" required></div>
                            <div class="col-md-6"><label>Surname *</label><input type="text" name="surname"
                                    class="form-control" required></div>
                            <div class="col-md-6"><label>Father Name</label><input type="text" name="father_name"
                                    class="form-control"></div>
                            <div class="col-md-6"><label>CNIC Number</label>
                                <input type="text" name="cnic" id="cnic" class="form-control"
                                    placeholder="41300-1234567-1" maxlength="15" required>

                                <script>
                                    document.getElementById('cnic').addEventListener('input', function (e) {
                                        var x = e.target.value.replace(/\D/g, '').match(/(\d{0,5})(\d{0,7})(\d{0,1})/);
                                        e.target.value = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '');
                                    });
                                </script>
                            </div>

                            <div class="col-md-6">
                                <label>Date of Birth (18+)</label>
                                <input type="date" name="dob" class="form-control" max="<?php echo $max_date; ?>">
                            </div>

                            <div class="col-md-6"><label>Gender</label>
                                <select name="gender" class="form-select">
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="col-12"><label>Address</label><textarea name="address" class="form-control"
                                    rows="2"></textarea></div>
                            <div class="col-12">
                                <label class="text-primary fw-bold">Profile Photo (Max 2MB)</label>
                                <input type="file" name="profile_photo" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Student</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>