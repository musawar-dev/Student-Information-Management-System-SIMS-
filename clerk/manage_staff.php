<?php
session_start();
include '../db_connect.php';

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Clerk') { header("Location: ../login.php"); exit(); }

$msg = "";

// --- ADD STAFF LOGIC ---
if(isset($_POST['add_staff'])){
    $name = $_POST['full_name'];
    $desig = $_POST['designation'];
    $phone = $_POST['phone'];
    $salary = $_POST['salary'];

    $sql = "INSERT INTO Staff (full_name, designation, phone, salary) VALUES ('$name', '$desig', '$phone', '$salary')";
    
    if($conn->query($sql)){
        $msg = "<div class='alert alert-success alert-dismissible fade show border-0 shadow-sm'>
                    <i class='fa fa-check-circle me-2'></i> New Staff Added Successfully!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                </div>";
    } else {
        $msg = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// --- DELETE LOGIC ---
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM Staff WHERE staff_id='$id'");
    header("Location: manage_staff.php"); // Refresh page
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Staff</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        /* SIDEBAR */
        .sidebar { 
            height: 100vh; width: 260px; position: fixed; top: 0; left: 0; 
            background: linear-gradient(180deg, #212529 0%, #343a40 100%); 
            z-index: 1000; transition: all 0.3s; 
            display: flex; flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar-header { 
            padding: 25px 20px; text-align: center; 
            background: rgba(0,0,0,0.2); border-bottom: 1px solid rgba(255,255,255,0.05); 
        }
        .sidebar-link { 
            padding: 15px 25px; text-decoration: none; color: #adb5bd; 
            display: block; border-left: 4px solid transparent; transition: 0.3s; font-weight: 500;
        }
        .sidebar-link:hover, .sidebar-link.active { 
            background: rgba(255,255,255,0.1); color: white; border-left: 4px solid #0d6efd; 
        }

        /* CONTENT */
        .main-content { margin-left: 260px; padding: 30px; margin-top: 70px; transition: 0.3s; }
        .top-navbar { 
            position: fixed; top: 0; left: 260px; right: 0; height: 70px; 
            background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
            z-index: 999; display: flex; align-items: center; padding: 0 30px; 
            justify-content: space-between; transition: 0.3s; 
        }

        /* CARD HOVER EFFECT */
        .hover-card { transition: transform 0.3s; }
        .hover-card:hover { transform: translateY(-5px); }
        
        @media (max-width: 768px) {
            .sidebar { left: -260px; }
            .sidebar.active { left: 0; }
            .main-content { margin-left: 0; }
            .top-navbar { left: 0; }
        }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="../uploads/logo.png" class="d-block mx-auto shadow-sm" 
             style="width: 80px; height: 80px; object-fit: contain; background: white; border-radius: 50%; padding: 5px; margin-bottom: 10px; border: 3px solid rgba(255,255,255,0.2);">
        <h5 class="mb-0 fw-bold text-white tracking-wide">Clerk Panel</h5>
    </div>
    
    <div class="p-2">
        <a href="dashboard.php" class="sidebar-link"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="teacher_attendance.php" class="sidebar-link"><i class="fa fa-chalkboard-teacher me-2"></i> Teacher Attendance</a>
        <a href="manage_staff.php" class="sidebar-link active"><i class="fa fa-broom me-2"></i> Manage Staff</a>
        <a href="reset_password.php" class="sidebar-link"><i class="fa fa-key me-2"></i> Reset Passwords</a>
        <a href="reports.php" class="sidebar-link"><i class="fa fa-file-excel me-2"></i> Reports & Export</a>
    </div>

    <div class="p-3 mt-auto">
        <a href="../logout.php" class="btn btn-outline-danger w-100 fw-bold"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="top-navbar">
    <button class="btn btn-light d-md-none shadow-sm" onclick="document.getElementById('sidebar').classList.toggle('active')">
        <i class="fa fa-bars"></i>
    </button>
    <h4 class="mb-0 fw-bold text-dark">Manage Staff</h4>
</div>

<div class="main-content">
    
    <?php echo $msg; ?>

    <div class="row g-4">
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 hover-card h-100">
                <div class="card-header bg-primary text-white p-3 rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fa fa-user-plus me-2"></i> Add New Staff</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Full Name</label>
                            <input type="text" name="full_name" class="form-control" placeholder="e.g. Aslam Khan" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Designation</label>
                            <select name="designation" class="form-select" required>
                                <option value="" disabled selected>Select Role</option>
                                <option>Peon</option>
                                <option>Sweeper</option>
                                <option>Security Guard</option>
                                <option>Gardener (Maali)</option>
                                <option>Driver</option>
                                <option>Lab Assistant</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="0300-1234567">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">Monthly Salary (Rs)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rs.</span>
                                <input type="number" name="salary" class="form-control" placeholder="25000" required>
                            </div>
                        </div>

                        <button type="submit" name="add_staff" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                            Add Staff Member
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 hover-card h-100">
                <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa fa-list me-2 text-primary"></i> Staff List</h5>
                    <span class="badge bg-light text-dark border">
                        Total: <?php echo $conn->query("SELECT count(*) as c FROM Staff")->fetch_assoc()['c']; ?>
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th class="ps-4">Name</th>
                                    <th>Role</th>
                                    <th>Phone</th>
                                    <th>Salary</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = $conn->query("SELECT * FROM Staff ORDER BY staff_id DESC");
                                if($res->num_rows > 0){
                                    while($row = $res->fetch_assoc()){
                                        echo '<tr>
                                            <td class="ps-4 fw-bold text-dark">'.$row['full_name'].'</td>
                                            <td><span class="badge bg-info text-dark bg-opacity-25 border border-info">'.$row['designation'].'</span></td>
                                            <td class="text-muted small"><i class="fa fa-phone me-1"></i> '.$row['phone'].'</td>
                                            <td class="fw-bold text-success">Rs. '.number_format($row['salary']).'</td>
                                            <td class="text-center">
                                                <a href="manage_staff.php?delete='.$row['staff_id'].'" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm(\'Are you sure you want to remove this staff member?\')">
                                                    <i class="fa fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="5" class="text-center p-5 text-muted">No staff members found.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>