<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Teacher') {
    header("Location: ../login.php");
    exit();
}

$u_id = $_SESSION['user_id'];
$teacher = $conn->query("SELECT * FROM Teachers WHERE user_id = '$u_id'")->fetch_assoc();

// --- SALARY CALCULATION LOGIC ---
// 1. Teacher ki ID nikalo
$emp_id = $teacher['employee_id'];
$fixed_salary = $teacher['salary'];

// 2. Is Mahinay (Current Month) ki Absents count karo
// Hum 'teacher_attendance' table check karenge
$current_month = date('Y-m'); // e.g., 2026-01

$absent_query = "SELECT COUNT(*) as total_absents 
                     FROM teacher_attendance 
                     WHERE employee_id = '$emp_id' 
                     AND status = 'Absent' 
                     AND DATE_FORMAT(attendance_date, '%Y-%m') = '$current_month'";

$absent_res = $conn->query($absent_query);
$absent_data = $absent_res->fetch_assoc();

// Yahan asli absences aayengi
$leaves_taken = $absent_data['total_absents'];

// 3. Calculation Variables
$allowed_leaves = 3;
$deduction_per_leave = 500;

// 4. Main Formula
if ($leaves_taken > $allowed_leaves) {
    $extra_leaves = $leaves_taken - $allowed_leaves;
    $total_deduction = $extra_leaves * $deduction_per_leave;
} else {
    $extra_leaves = 0;
    $total_deduction = 0;
}

$net_salary = $fixed_salary - $total_deduction;
// --------------------------------
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            height: 100vh;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #212529 0%, #343a40 100%);
            z-index: 1000;
            transition: all 0.3s;

            /* YE 2 LINES ZAROORI HAIN LOGOUT KO NEECHE RAKHNE KE LIYE */
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-link {
            padding: 12px 25px;
            text-decoration: none;
            color: #adb5bd;
            display: block;
            border-left: 4px solid transparent;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left: 4px solid #0d6efd;
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
        }

        /* --- NEW CARD DESIGNS --- */
        .dashboard-card {
            border: none;
            border-radius: 20px;
            color: white;
            padding: 30px;
            position: relative;
            overflow: hidden;
            /* Icon ko card ke andar rakhne ke liye */
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            /* Smooth Bounce Effect */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        /* HOVER EFFECT */
        .dashboard-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        /* Salary Card (Green Gradient) */
        .card-salary {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        /* Courses Card (Purple/Blue Gradient) - Redesigned */
        .card-courses {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Background Giant Icons */
        .card-icon-bg {
            position: absolute;
            right: -20px;
            bottom: -20px;
            font-size: 150px;
            opacity: 0.15;
            transform: rotate(15deg);
            pointer-events: none;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .sidebar {
                left: -260px;
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .top-navbar {
                left: 0;
            }
        }
    </style>
</head>

<body>

    <button class="btn btn-primary mobile-btn shadow"
        onclick="document.querySelector('.sidebar').classList.toggle('active')">
        <i class="fa fa-bars"></i>
    </button>

    <div class="sidebar">
        <div class="sidebar-header text-center">
            <img src="../uploads/logo.png" class="d-block mx-auto shadow-sm"
                style="width: 80px; height: 80px; object-fit: contain; background: white; border-radius: 50%; padding: 5px; margin-bottom: 10px; border: 3px solid rgba(255,255,255,0.2);">

            <h5 class="mb-0 fw-bold text-white tracking-wide">Teacher Panel</h5>
        </div>
        <div class="p-2">
            <a href="dashboard.php" class="sidebar-link active"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="profile.php" class="sidebar-link"><i class="fa fa-user me-2"></i> My Profile</a>
            <a href="my_courses.php" class="sidebar-link"><i class="fa fa-book me-2"></i> My Courses</a>
        </div>
        <div class="p-3 mt-auto">
            <a href="../logout.php" class="btn btn-outline-danger w-100"><i class="fa fa-sign-out-alt me-2"></i>
                Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm">
            <div>
                <h2 class="fw-bold mb-0 text-dark">Welcome Back, <?php echo $teacher['first_name']; ?>!</h2>
                <p class="text-muted mb-0">Here is your performance overview.</p>
            </div>
            <form action="search.php" method="GET" class="mt-3 mb-2" style="max-width: 300px;">
                <div class="input-group">
                    <input type="text" name="q" class="form-control rounded-pill" placeholder="Search Student..."
                        required>
                    <button class="btn btn-primary rounded-pill ms-1"><i class="fa fa-search"></i></button>
                </div>
            </form>
            <div class="text-end">
                <h5 class="fw-bold text-primary mb-0"><?php echo date("l"); ?></h5>
                <small class="text-muted"><?php echo date("d F Y"); ?></small>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-md-6">
                <div class="dashboard-card card-salary">
                    <h4 class="mb-4">
                        <i class="fa fa-file-invoice-dollar me-2"></i> Salary Report
                        <small class="text-muted" style="font-size: 12px;">(<?php echo date('M Y'); ?>)</small>
                    </h4>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="stat-label">Base Salary</span>
                        <span class="fw-bold fs-5">Rs. <?php echo number_format($fixed_salary); ?></span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="stat-label">Absents (Allowed: 3)</span>
                        <span class="badge <?php echo ($leaves_taken > 3) ? 'bg-danger' : 'bg-success'; ?> fs-6">
                            <?php echo $leaves_taken; ?> Days
                        </span>
                    </div>

                    <?php if ($total_deduction > 0): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2 text-danger"
                            style="background: rgba(232, 236, 19, 0.76); padding: 5px 10px; border-radius: 8px;">
                            <span><i class="fa fa-minus-circle"></i> Fine (<?php echo $extra_leaves; ?> extra days)</span>
                            <span class="fw-bold">- Rs. <?php echo number_format($total_deduction); ?></span>
                        </div>
                    <?php else: ?>
                        <div class="d-flex justify-content-between align-items-center mb-2 text-success"
                            style="font-size: 13px;">
                            <span><i class="fa fa-check-circle"></i> No Deductions</span>
                            <span>Rs. 0</span>
                        </div>
                    <?php endif; ?>

                    <hr style="opacity: 0.3;">

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="stat-label">Net Payable</span>
                        <span class="stat-value text-primary">Rs. <?php echo number_format($net_salary); ?></span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <?php
                $emp_id = $teacher['employee_id'];
                $c_count = $conn->query("SELECT count(*) as c FROM Allocations WHERE employee_id='$emp_id'")->fetch_assoc()['c'];
                ?>
                <a href="my_courses.php" class="text-decoration-none">
                    <div class="dashboard-card card-courses">
                        <i class="fa fa-book-open card-icon-bg"></i>
                        <h4 class="mb-4"><i class="fa fa-chalkboard me-2"></i> Workload</h4>

                        <div class="mt-2">
                            <span class="stat-label d-block">Total Assigned Courses</span>
                            <span class="stat-value display-3"><?php echo $c_count; ?></span>
                        </div>

                        <div class="mt-4">
                            <span class="btn btn-light text-primary rounded-pill fw-bold px-4 shadow-sm">
                                Manage Marks <i class="fa fa-arrow-right ms-2"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>