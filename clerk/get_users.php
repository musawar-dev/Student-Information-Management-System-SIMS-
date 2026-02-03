<?php
include '../db_connect.php';

$role = isset($_POST['role']) ? $_POST['role'] : 'Student';
$q = isset($_POST['q']) ? $conn->real_escape_string($_POST['q']) : '';
$batch = isset($_POST['batch']) ? $_POST['batch'] : '';

// 1. STUDENTS LIVE SEARCH
if($role == 'Student'){
    $sql = "SELECT Students.*, Batches.batch_name FROM Students 
            LEFT JOIN Batches ON Students.batch_id = Batches.batch_id 
            WHERE (first_name LIKE '%$q%' OR surname LIKE '%$q%' OR roll_no LIKE '%$q%')";
    if(!empty($batch)) $sql .= " AND Students.batch_id = '$batch'";
    
    $res = $conn->query($sql);
    if($res->num_rows > 0){
        while($row = $res->fetch_assoc()){
            echo "<tr>
                <td class='ps-4'>
                    <div class='fw-bold text-dark'>".$row['first_name']." ".$row['surname']."</div>
                    <small class='text-muted'>".$row['roll_no']."</small>
                </td>
                <td><span class='badge bg-light text-dark border'>".$row['batch_name']."</span></td>
                <td>".$row['father_name']."</td>
                <td class='text-end pe-4'>
                    <button class='btn btn-sm btn-outline-dark shadow-sm' onclick='openExportModal(\"Student\", ".$row['user_id'].")'>
                        <i class='fa fa-print me-1'></i> Report
                    </button>
                </td>
            </tr>";
        }
    } else { echo "<tr><td colspan='4' class='text-center text-muted py-4'>No student found matching '$q'</td></tr>"; }
}

// 2. TEACHERS LIVE SEARCH
elseif($role == 'Teacher'){
    $sql = "SELECT * FROM Teachers WHERE (first_name LIKE '%$q%' OR last_name LIKE '%$q%' OR employee_id LIKE '%$q%')";
    $res = $conn->query($sql);
    if($res->num_rows > 0){
        while($row = $res->fetch_assoc()){
            echo "<tr>
                <td class='ps-4'>
                    <div class='fw-bold text-dark'>".$row['first_name']." ".$row['last_name']."</div>
                    <small class='text-muted'>".$row['employee_id']."</small>
                </td>
                <td>".$row['designation']."</td>
                <td>".$row['email']."</td>
                <td class='text-end pe-4'>
                    <button class='btn btn-sm btn-outline-dark shadow-sm' onclick='openExportModal(\"Teacher\", ".$row['user_id'].")'>
                        <i class='fa fa-print me-1'></i> Report
                    </button>
                </td>
            </tr>";
        }
    } else { echo "<tr><td colspan='4' class='text-center text-muted py-4'>No teacher found matching '$q'</td></tr>"; }
}

// 3. STAFF LIVE SEARCH
elseif($role == 'Staff'){
    $sql = "SELECT * FROM Staff WHERE full_name LIKE '%$q%'";
    $res = $conn->query($sql);
    if($res->num_rows > 0){
        while($row = $res->fetch_assoc()){
            echo "<tr>
                <td class='ps-4 fw-bold text-dark'>".$row['full_name']."</td>
                <td>".$row['designation']."</td>
                <td>".$row['phone']."</td>
                <td class='text-end pe-4'>
                    <button class='btn btn-sm btn-outline-dark shadow-sm' onclick='openExportModal(\"Staff\", ".$row['staff_id'].")'>
                        <i class='fa fa-print me-1'></i> Report
                    </button>
                </td>
            </tr>";
        }
    } else { echo "<tr><td colspan='4' class='text-center text-muted py-4'>No staff found.</td></tr>"; }
}
?>