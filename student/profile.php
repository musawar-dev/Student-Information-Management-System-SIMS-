<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Student') { header("Location: ../login.php"); exit(); }

$u_id = $_SESSION['user_id'];

// Fetch Data (Students.* ab naye columns bhi uthayega)
$sql = "SELECT Students.*, Batches.batch_name, Users.email 
        FROM Students 
        LEFT JOIN Batches ON Students.batch_id = Batches.batch_id 
        JOIN Users ON Students.user_id = Users.user_id 
        WHERE Students.user_id = '$u_id'";

$res = $conn->query($sql);
$row = $res->fetch_assoc();

$img = !empty($row['profile_photo']) ? "../uploads/".$row['profile_photo'] : "../uploads/default_student.png";

// Agar Admission Date database me NULL ho to Default Text
$adm_date = ($row['admission_date']) ? date("d F Y", strtotime($row['admission_date'])) : "Not Available";
$phone = ($row['phone_no']) ? $row['phone_no'] : "Not Updated";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Profile</title>
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
        
        .profile-label { font-size: 0.85rem; font-weight: 700; text-transform: uppercase; color: #6c757d; letter-spacing: 0.5px; }
        .profile-value { font-size: 1.1rem; font-weight: 500; color: #212529; }
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
        <a href="my_courses.php" class="sidebar-link"><i class="fa fa-book me-2"></i> My Courses</a>
        <a href="profile.php" class="sidebar-link active"><i class="fa fa-id-card me-2"></i> Profile</a>
    </div>
    <div class="logout-container">
        <a href="../logout.php" class="btn-logout"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                
                <div class="card-header bg-dark text-white p-5 text-center position-relative">
                    <img src="<?php echo $img; ?>" class="rounded-circle border border-4 border-white shadow mb-3" width="150" height="150" style="object-fit:cover;">
                    <h2 class="fw-bold mb-1"><?php echo $row['first_name']." ".$row['surname']; ?></h2>
                    <p class="mb-0 fs-5 opacity-75"><i class="fa fa-id-badge me-2"></i><?php echo $row['roll_no']; ?></p>
                    <span class="badge bg-primary mt-2 px-3 py-2 rounded-pill"><?php echo $row['batch_name']; ?></span>
                </div>

                <div class="card-body p-5">
                    <h5 class="fw-bold text-primary mb-4 border-bottom pb-2"><i class="fa fa-info-circle me-2"></i> Personal Information</h5>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="profile-label">Full Name</div>
                            <div class="profile-value"><?php echo $row['first_name'] . " " . $row['surname']; ?></div>
                        </div>

                        <div class="col-md-6">
                            <div class="profile-label">Father's Name</div>
                            <div class="profile-value"><?php echo $row['father_name']; ?></div>
                        </div>

                        <div class="col-md-6">
                            <div class="profile-label">Email Address</div>
                            <div class="profile-value text-primary">
                                <i class="fa fa-envelope me-2"></i><?php echo $row['email']; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="profile-label">Phone Number</div>
                            <div class="profile-value">
                                <i class="fa fa-phone me-2 text-success"></i><?php echo $phone; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="profile-label">Gender</div>
                            <div class="profile-value"><?php echo $row['gender']; ?></div>
                        </div>

                        <div class="col-md-6">
                            <div class="profile-label">CNIC Number</div>
                            <div class="profile-value"><?php echo $row['cnic']; ?></div>
                        </div>

                        <div class="col-md-6">
                            <div class="profile-label">Date of Birth</div>
                            <div class="profile-value">
                                <i class="fa fa-birthday-cake me-2 text-warning"></i>
                                <?php echo date("d M Y", strtotime($row['dob'])); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="profile-label">Admission Date</div>
                            <div class="profile-value">
                                <i class="fa fa-calendar-check me-2 text-info"></i>
                                <?php echo $adm_date; ?>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="profile-label">Permanent Address</div>
                            <div class="profile-value">
                                <i class="fa fa-map-marker-alt me-2 text-danger"></i>
                                <?php echo $row['address']; ?>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light mt-5 border-start border-4 border-info shadow-sm">
                        <div class="d-flex">
                            <i class="fa fa-lock fa-2x me-3 text-info"></i>
                            <div>
                                <strong>Privacy Notice:</strong><br>
                                This information is managed by the University Administration. If you find any discrepancy, please visit the Admin Office with your original documents.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>