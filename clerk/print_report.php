<?php
session_start();
include '../db_connect.php';
if ($_SESSION['role'] != 'Clerk') { die("Access Denied"); }

$role = $_POST['role'];
$target_id = $_POST['target_id'];
$fields = isset($_POST['fields']) ? $_POST['fields'] : [];
$batch_id = isset($_POST['batch_id']) ? $_POST['batch_id'] : '';

function show($key) { global $fields; return in_array($key, $fields); }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: white; padding: 40px; font-family: 'Times New Roman', serif; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { width: 80px; height: 80px; object-fit: contain; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #444; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn btn-primary px-4 fw-bold">Print / Save PDF</button>
        <button onclick="window.close()" class="btn btn-secondary px-4 ms-2">Close</button>
    </div>

    <div class="header">
        <img src="../uploads/logo.png" class="logo">
        <h3>Software Engineering Department (QUEST)</h3>
        <h5><?php echo $role; ?> Report</h5>
        <small>Generated on: <?php echo date("d M Y, h:i A"); ?></small>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="30">#</th>
                <?php
                if($role == 'Student'){
                    if(show('roll_no')) echo "<th>Roll No</th>";
                    if(show('first_name')) echo "<th>First Name</th>";
                    if(show('surname')) echo "<th>Surname</th>";
                    if(show('batch_name')) echo "<th>Batch</th>";
                    if(show('father_name')) echo "<th>Father Name</th>";
                    if(show('cnic')) echo "<th>CNIC</th>";
                    if(show('phone_number')) echo "<th>Phone</th>";
                    if(show('email')) echo "<th>Email</th>";
                    if(show('address')) echo "<th>Address</th>";
                    if(show('gender')) echo "<th>Gender</th>";
                    if(show('dob')) echo "<th>DOB</th>";
                }
                elseif($role == 'Teacher'){
                    if(show('employee_id')) echo "<th>Emp ID</th>";
                    if(show('first_name')) echo "<th>First Name</th>";
                    if(show('last_name')) echo "<th>Last Name</th>";
                    if(show('designation')) echo "<th>Designation</th>";
                    if(show('email')) echo "<th>Email</th>";
                    if(show('phone')) echo "<th>Phone</th>";
                    if(show('gender')) echo "<th>Gender</th>";
                    if(show('qualification')) echo "<th>Qualif.</th>";
                    if(show('salary')) echo "<th>Salary</th>";
                    if(show('att_stats')) echo "<th>Attendance</th>";
                }
                elseif($role == 'Staff'){
                    if(show('full_name')) echo "<th>Name</th>";
                    if(show('designation')) echo "<th>Role</th>";
                    if(show('phone')) echo "<th>Phone</th>";
                    if(show('join_date')) echo "<th>Joined</th>";
                    if(show('salary')) echo "<th>Salary</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1;

            if($role == 'Student'){
                $q = "SELECT Students.*, Batches.batch_name FROM Students LEFT JOIN Batches ON Students.batch_id = Batches.batch_id";
                $conds = [];
                if($target_id != 'bulk') $conds[] = "user_id = '$target_id'";
                if($target_id == 'bulk' && $batch_id) $conds[] = "Students.batch_id = '$batch_id'";
                if(!empty($conds)) $q .= " WHERE " . implode(" AND ", $conds);

                $res = $conn->query($q);
                while($row = $res->fetch_assoc()){
                    // Safe Check for fields that might be NULL
                    $ph = isset($row['phone_number']) ? $row['phone_number'] : '-';
                    $addr = isset($row['address']) ? $row['address'] : '-';
                    $gen = isset($row['gender']) ? $row['gender'] : '-';
                    $dob = isset($row['dob']) ? $row['dob'] : '-';
                    $cnic = isset($row['cnic']) ? $row['cnic'] : '-';
                    $email = isset($row['email']) ? $row['email'] : '-';

                    echo "<tr><td>".$count++."</td>";
                    if(show('roll_no')) echo "<td>".$row['roll_no']."</td>";
                    if(show('first_name')) echo "<td>".$row['first_name']."</td>";
                    if(show('surname')) echo "<td>".$row['surname']."</td>";
                    if(show('batch_name')) echo "<td>".$row['batch_name']."</td>";
                    if(show('father_name')) echo "<td>".$row['father_name']."</td>";
                    if(show('cnic')) echo "<td>$cnic</td>";
                    if(show('phone_number')) echo "<td>$ph</td>";
                    if(show('email')) echo "<td>$email</td>";
                    if(show('address')) echo "<td>$addr</td>";
                    if(show('gender')) echo "<td>$gen</td>";
                    if(show('dob')) echo "<td>$dob</td>";
                    echo "</tr>";
                }
            }
            elseif($role == 'Teacher'){
                $q = "SELECT * FROM Teachers";
                if($target_id != 'bulk') $q .= " WHERE user_id = '$target_id'";
                $res = $conn->query($q);
                while($row = $res->fetch_assoc()){
                    $eid = $row['employee_id'];
                    $att = $conn->query("SELECT count(*) as c FROM Teacher_Attendance WHERE employee_id='$eid' AND status='Present'")->fetch_assoc()['c'];
                    $ph = isset($row['phone']) ? $row['phone'] : '-';
                    $gen = isset($row['gender']) ? $row['gender'] : '-';
                    $qual = isset($row['qualification']) ? $row['qualification'] : '-';

                    echo "<tr><td>".$count++."</td>";
                    if(show('employee_id')) echo "<td>".$row['employee_id']."</td>";
                    if(show('first_name')) echo "<td>".$row['first_name']."</td>";
                    if(show('last_name')) echo "<td>".$row['last_name']."</td>";
                    if(show('designation')) echo "<td>".$row['designation']."</td>";
                    if(show('email')) echo "<td>".$row['email']."</td>";
                    if(show('phone')) echo "<td>$ph</td>";
                    if(show('gender')) echo "<td>$gen</td>";
                    if(show('qualification')) echo "<td>$qual</td>";
                    if(show('salary')) echo "<td>Rs. ".number_format($row['salary'])."</td>";
                    if(show('att_stats')) echo "<td>$att Days</td>";
                    echo "</tr>";
                }
            }
            elseif($role == 'Staff'){
                $q = "SELECT * FROM Staff";
                if($target_id != 'bulk') $q .= " WHERE staff_id = '$target_id'";
                $res = $conn->query($q);
                while($row = $res->fetch_assoc()){
                    $jd = isset($row['join_date']) ? $row['join_date'] : '-';
                    echo "<tr><td>".$count++."</td>";
                    if(show('full_name')) echo "<td>".$row['full_name']."</td>";
                    if(show('designation')) echo "<td>".$row['designation']."</td>";
                    if(show('phone')) echo "<td>".$row['phone']."</td>";
                    if(show('join_date')) echo "<td>$jd</td>";
                    if(show('salary')) echo "<td>Rs. ".number_format($row['salary'])."</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</body>
</html>