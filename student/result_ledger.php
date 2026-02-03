<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Student') { header("Location: ../login.php"); exit(); }

$u_id = $_SESSION['user_id'];

// 1. Fetch Student & Batch Info
$s_res = $conn->query("SELECT Students.*, Batches.batch_name FROM Students 
                       JOIN Batches ON Students.batch_id = Batches.batch_id 
                       WHERE Students.user_id = '$u_id'");

if($s_res->num_rows == 0) { die("Student Record Missing."); }
$student = $s_res->fetch_assoc();
$roll_no = $student['roll_no'];
$batch_name = $student['batch_name']; // e.g., "24-Batch" or "21SW"

// --- 2. SMART SEMESTER CALCULATOR ---
// Batch Name se Year nikalna (Assuming format starts with Year like '21' or '24')
// '21-Batch' -> 21 -> 2021
$batch_year_prefix = intval(preg_replace('/[^0-9]/', '', substr($batch_name, 0, 2))); 
$start_year = 2000 + $batch_year_prefix; // 2024 or 2021

$current_month = date('n'); // 1 to 12
$current_year = date('Y');

// Years ka farq nikalo
$year_diff = $current_year - $start_year;

// Semester Logic:
// Year 0 (Start): Oct-Feb (Sem 1), Mar-Sep (Sem 2)
// Year 1: Oct-Feb (Sem 3), Mar-Sep (Sem 4)
// Formula based on your Odd/Even months
if ($current_month >= 3 && $current_month <= 9) {
    // Even Semester Period (March to Sept)
    $current_sem = $year_diff * 2;
} else {
    // Odd Semester Period (Oct to Feb)
    // Agar Jan/Feb hai (e.g., Jan 2026), to ye previous year ka continuation hai
    if ($current_month <= 2) {
        $current_sem = ($year_diff * 2) - 1;
    } else {
        // Oct/Nov/Dec
        $current_sem = ($year_diff * 2) + 1;
    }
}

// Safety Checks
if ($current_sem < 1) $current_sem = 1;
if ($current_sem > 8) $current_sem = 8; // Max 8 Semesters

// --- FILTER LOGIC ---
$selected_sem = isset($_GET['sem']) ? $_GET['sem'] : $current_sem;

// --- 3. GRADING FUNCTIONS (Your Policy) ---
function get_grade_point_value($marks) {
    if ($marks >= 90) return 4.00;
    if ($marks >= 81) return 3.50;
    if ($marks >= 73) return 3.00;
    if ($marks >= 65) return 2.50;
    if ($marks >= 60) return 2.00;
    if ($marks >= 55) return 1.50;
    if ($marks >= 50) return 1.00;
    return 0.00; // Fail
}

function get_grade_letter($marks) {
    if ($marks >= 90) return "A+";
    if ($marks >= 81) return "A";
    if ($marks >= 73) return "B+";
    if ($marks >= 65) return "B";
    if ($marks >= 60) return "C+";
    if ($marks >= 55) return "C";
    if ($marks >= 50) return "C-";
    return "F";
}

// --- 4. CALCULATE CGPA (Overall History) ---
// Is query me hum wo sary courses uthayenge jo student ne ab tak parhay hn (Current + Previous)
$all_courses_q = $conn->query("SELECT Enrollments.*, Courses.credit_hours 
                               FROM Enrollments 
                               JOIN Courses ON Enrollments.course_code = Courses.course_code 
                               WHERE Enrollments.roll_no = '$roll_no'");

$total_qp_cgpa = 0;
$total_ch_cgpa = 0;

while($ac = $all_courses_q->fetch_assoc()){
    $obt = $ac['sessional_marks'] + $ac['mid_marks'] + $ac['final_marks'];
    // Sirf tab count karein agar marks upload hue hn (Not 0)
    if ($obt > 0) {
        $gp_val = get_grade_point_value($obt);
        $ch = $ac['credit_hours'];
        
        $total_qp_cgpa += ($gp_val * $ch); // Quality Points = GP * Credit Hour
        $total_ch_cgpa += $ch;
    }
}
$cgpa = ($total_ch_cgpa > 0) ? number_format($total_qp_cgpa / $total_ch_cgpa, 2) : "0.00";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Result Ledger</title>
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

        /* RESULT DESIGN */
        .result-header { background: linear-gradient(135deg, #0d6efd, #0043a8); color: white; padding: 25px; border-radius: 15px 15px 0 0; }
        .stat-box { background: #fff; padding: 15px; border-radius: 10px; border-left: 5px solid; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .stat-gpa { border-color: #0d6efd; }
        .stat-perc { border-color: #198754; }
        .grade-badge { font-weight: bold; width: 40px; display: inline-block; text-align: center; }
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
        <a href="result_ledger.php" class="sidebar-link active"><i class="fa fa-file-invoice me-2"></i> Result Ledger</a>
        <a href="my_courses.php" class="sidebar-link"><i class="fa fa-book me-2"></i> My Courses</a>
        <a href="profile.php" class="sidebar-link"><i class="fa fa-id-card me-2"></i> Profile</a>
    </div>
    <div class="logout-container">
        <a href="../logout.php" class="btn-logout"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Academic Result</h3>
            <p class="text-muted small">Batch: <?php echo $batch_name; ?> | Current Semester: <?php echo $current_sem; ?></p>
        </div>
        <form method="GET" class="d-flex align-items-center bg-white p-2 rounded shadow-sm">
            <label class="me-2 fw-bold text-secondary">View Semester:</label>
            <select name="sem" class="form-select w-auto border-0 bg-light fw-bold" onchange="this.form.submit()">
                <?php
                // Dropdown me 1 se lekar Current Sem tak dikhao
                for ($i = 1; $i <= $current_sem; $i++) {
                    $sel = ($i == $selected_sem) ? "selected" : "";
                    echo "<option value='$i' $sel>Semester $i</option>";
                }
                ?>
            </select>
        </form>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        
        <div class="result-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold">Semester <?php echo $selected_sem; ?></h2>
                <span class="opacity-75">Marksheet & GPA Report</span>
            </div>
            <div class="text-center bg-white bg-opacity-25 p-3 rounded-3">
                <small class="text-uppercase ls-1">Overall CGPA</small>
                <div class="display-6 fw-bold"><?php echo $cgpa; ?></div>
            </div>
        </div>
        
        <div class="card-body p-4">
            <table class="table table-hover align-middle border-bottom">
                <thead class="table-light text-uppercase small text-muted">
                    <tr>
                        <th>Course</th>
                        <th class="text-center">C.H</th>
                        <th class="text-center">Sessional</th>
                        <th class="text-center">Mid</th>
                        <th class="text-center">Final</th>
                        <th class="text-center fw-bold text-dark">Total</th>
                        <th class="text-center">Grade</th>
                        <th class="text-center">Value</th>
                        <th class="text-center">Q.Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch Data for Selected Semester
                    // NOTE: Hum sirf un courses ko uthayenge jo "Assign" ho chuke hain ya "Enroll" hain.
                    // Ideal ye hai ke Student ke Enrollments table se uthayen.
                    
                    // Pehle check karein ke kya student enrolled hai us semester ke courses me?
                    // Agar nahi, to Courses table se direct utha ke "Not Appeared" dikha sakty hain.
                    // Lekin filhal Enrollments se uthate hain.

                    $res_sql = "SELECT Enrollments.*, Courses.course_title, Courses.course_code, Courses.credit_hours 
                                FROM Enrollments 
                                JOIN Courses ON Enrollments.course_code = Courses.course_code 
                                WHERE Enrollments.roll_no = '$roll_no' AND Courses.semester = '$selected_sem'";
                    
                    $res_q = $conn->query($res_sql);
                    
                    // Semester Totals Calculation
                    $sem_total_qp = 0;
                    $sem_total_ch = 0;
                    $sem_total_marks_obt = 0;
                    $sem_total_marks_max = 0;

                    if ($res_q->num_rows > 0) {
                        while ($row = $res_q->fetch_assoc()) {
                            $marks_obt = $row['sessional_marks'] + $row['mid_marks'] + $row['final_marks'];
                            
                            // Grade & QP Logic
                            if($marks_obt > 0) {
                                $grade = get_grade_letter($marks_obt);
                                $val = get_grade_point_value($marks_obt);
                                $qp = $val * $row['credit_hours'];
                                $q_point_display = number_format($qp, 1);
                                
                                // Totals update
                                $sem_total_qp += $qp;
                                $sem_total_ch += $row['credit_hours'];
                                $sem_total_marks_obt += $marks_obt;
                                $sem_total_marks_max += 100; // Assuming 100 per course
                                
                                // Color Badge
                                $bg = ($grade == 'F') ? 'bg-danger' : (($grade == 'A+' || $grade == 'A') ? 'bg-success' : 'bg-primary');
                                $badge = "<span class='badge $bg grade-badge'>$grade</span>";

                            } else {
                                $grade = "-";
                                $val = "0.00";
                                $q_point_display = "-";
                                $badge = "<span class='badge bg-secondary'>Pending</span>";
                            }

                            echo "<tr>
                                <td>
                                    <div class='fw-bold text-dark'>".$row['course_title']."</div>
                                    <small class='text-muted'>".$row['course_code']."</small>
                                </td>
                                <td class='text-center'>".$row['credit_hours']."</td>
                                <td class='text-center text-muted'>".$row['sessional_marks']."</td>
                                <td class='text-center text-muted'>".$row['mid_marks']."</td>
                                <td class='text-center text-muted'>".$row['final_marks']."</td>
                                <td class='text-center fw-bold'>$marks_obt</td>
                                <td class='text-center'>$badge</td>
                                <td class='text-center small'>$val</td>
                                <td class='text-center fw-bold text-primary'>$q_point_display</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center p-5 text-muted'>
                                <i class='fa fa-folder-open fa-2x mb-3 d-block opacity-25'></i>
                                No result record found for Semester $selected_sem.
                              </td></tr>";
                    }

                    // FINAL SEMESTER CALCULATIONS
                    $sem_gpa = ($sem_total_ch > 0) ? number_format($sem_total_qp / $sem_total_ch, 2) : "0.00";
                    $sem_perc = ($sem_total_marks_max > 0) ? number_format(($sem_total_marks_obt / $sem_total_marks_max) * 100, 2) : "0.00";
                    ?>
                </tbody>
            </table>

            <div class="row mt-4">
                <div class="col-md-6"></div>
                <div class="col-md-3">
                    <div class="stat-box stat-perc">
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Percentage</small>
                        <div class="fs-4 fw-bold text-success"><?php echo $sem_perc; ?>%</div>
                        <small class="text-muted">Obtained: <?php echo $sem_total_marks_obt; ?> / <?php echo $sem_total_marks_max; ?></small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box stat-gpa">
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Semester GPA</small>
                        <div class="fs-4 fw-bold text-primary"><?php echo $sem_gpa; ?></div>
                        <small class="text-muted">Quality Points: <?php echo $sem_total_qp; ?></small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>