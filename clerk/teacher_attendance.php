<?php
session_start();
include '../db_connect.php';

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Clerk') { header("Location: ../login.php"); exit(); }

$msg = "";
// Default Date: Aaj ki date
$selected_date = date("Y-m-d");

// --- ATTENDANCE SAVE LOGIC ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $selected_date = $_POST['att_date']; // User selected date
    $marked_by = "Clerk"; 
    $today = date("Y-m-d");

    // 1. Future Date Check
    if ($selected_date > $today) {
        $msg = "<div class='alert alert-danger'>You cannot mark attendance for a future date!</div>";
    } 
    else {
        // 2. Duplicate Check (Kya is date ki attendance pehle lag chuki h?)
        $chk_sql = "SELECT * FROM Teacher_Attendance WHERE attendance_date = '$selected_date'";
        $chk_res = $conn->query($chk_sql);

        if ($chk_res->num_rows > 0) {
            $msg = "<div class='alert alert-warning'>Attendance for <b>$selected_date</b> is already marked! You cannot overwrite it.</div>";
        } 
        else {
            // 3. Insert Logic
            $present_teachers = isset($_POST['present_teachers']) ? $_POST['present_teachers'] : [];
            $all_teachers = $conn->query("SELECT employee_id FROM Teachers");
            
            $p_count = 0;
            $a_count = 0;

            while($t = $all_teachers->fetch_assoc()) {
                $eid = $t['employee_id'];
                
                // Logic: Agar array me hai to Present, nahi to Absent
                if (in_array($eid, $present_teachers)) {
                    $status = 'Present';
                    $p_count++;
                } else {
                    $status = 'Absent';
                    $a_count++;
                }

                $sql = "INSERT INTO Teacher_Attendance (employee_id, attendance_date, status, marked_by) 
                        VALUES ('$eid', '$selected_date', '$status', '$marked_by')";
                $conn->query($sql);
            }

            $msg = "<div class='alert alert-success alert-dismissible fade show border-0 shadow-sm'>
                        <i class='fa fa-check-circle me-2'></i> Attendance Saved for <b>$selected_date</b>!<br>
                        <small>Present: $p_count | Absent: $a_count</small>
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                    </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Teacher Attendance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        /* SIDEBAR (Same Pro Design) */
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
        <a href="teacher_attendance.php" class="sidebar-link active"><i class="fa fa-chalkboard-teacher me-2"></i> Teacher Attendance</a>
        <a href="manage_staff.php" class="sidebar-link"><i class="fa fa-broom me-2"></i> Manage Staff</a>
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
    <h4 class="mb-0 fw-bold text-dark">Teacher Attendance</h4>
</div>

<div class="main-content">

    <?php echo $msg; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        
        <form method="POST">
            <div class="card-header bg-white p-4 border-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-1">Select Date</h5>
                        <small class="text-muted">Choose a date to mark attendance.</small>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white"><i class="fa fa-calendar-alt"></i></span>
                            <input type="date" name="att_date" class="form-control fw-bold" 
                                   value="<?php echo $selected_date; ?>" 
                                   max="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4 py-3">Teacher Name</th>
                                <th>Designation</th>
                                <th class="text-center">Status (Check = Present)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch All Teachers
                            $t_sql = "SELECT * FROM Teachers ORDER BY first_name ASC";
                            $t_res = $conn->query($t_sql);

                            if($t_res->num_rows > 0){
                                while($row = $t_res->fetch_assoc()){
                                    
                                    // Default checked for easier marking? No, keep empty for accuracy.
                                    
                                    echo '<tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight:bold; color:#555;">
                                                    '.substr($row['first_name'],0,1).'
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">'.$row['first_name'].' '.$row['last_name'].'</div>
                                                    <small class="text-muted">ID: '.$row['employee_id'].'</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-info text-dark">'.$row['designation'].'</span></td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input border-2 border-secondary" type="checkbox" 
                                                       name="present_teachers[]" value="'.$row['employee_id'].'" 
                                                       style="transform: scale(1.4); cursor: pointer;">
                                            </div>
                                        </td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3" class="text-center p-5 text-muted">No teachers found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="p-4 bg-light border-top text-end">
                <button type="submit" class="btn btn-success px-5 py-2 fw-bold shadow">
                    <i class="fa fa-save me-2"></i> Save Attendance
                </button>
            </div>
        </form>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>