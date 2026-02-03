<?php
session_start();
include '../db_connect.php';
if ($_SESSION['role'] != 'Clerk') { header("Location: ../login.php"); exit(); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Advanced Reports</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        /* SIDEBAR CSS */
        .sidebar { height: 100vh; width: 260px; position: fixed; top: 0; left: 0; background: linear-gradient(180deg, #212529 0%, #343a40 100%); z-index: 1000; transition: all 0.3s; display: flex; flex-direction: column; }
        .sidebar-header { padding: 25px 20px; text-align: center; background: rgba(0,0,0,0.2); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .sidebar-link { padding: 15px 25px; text-decoration: none; color: #adb5bd; display: block; border-left: 4px solid transparent; transition: 0.3s; font-weight: 500; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(255,255,255,0.1); color: white; border-left: 4px solid #0d6efd; }
        .main-content { margin-left: 260px; padding: 30px; margin-top: 70px; transition: 0.3s; }
        .top-navbar { position: fixed; top: 0; left: 260px; right: 0; height: 70px; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); z-index: 999; display: flex; align-items: center; padding: 0 30px; justify-content: space-between; transition: 0.3s; }
        @media (max-width: 768px) { .sidebar { left: -260px; } .sidebar.active { left: 0; } .main-content { margin-left: 0; } .top-navbar { left: 0; } }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="../uploads/logo.png" class="d-block mx-auto shadow-sm" style="width: 80px; height: 80px; object-fit: contain; background: white; border-radius: 50%; padding: 5px; margin-bottom: 10px; border: 3px solid rgba(255,255,255,0.2);">
        <h5 class="mb-0 fw-bold text-white">Clerk Panel</h5>
    </div>
    <div class="p-2">
        <a href="dashboard.php" class="sidebar-link"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="teacher_attendance.php" class="sidebar-link"><i class="fa fa-chalkboard-teacher me-2"></i> Teacher Attendance</a>
        <a href="manage_staff.php" class="sidebar-link"><i class="fa fa-broom me-2"></i> Manage Staff</a>
        <a href="reset_password.php" class="sidebar-link"><i class="fa fa-key me-2"></i> Reset Passwords</a>
        <a href="reports.php" class="sidebar-link active"><i class="fa fa-file-excel me-2"></i> Reports & Export</a>
    </div>
    <div class="p-3 mt-auto"><a href="../logout.php" class="btn btn-outline-danger w-100 fw-bold">Logout</a></div>
</div>

<div class="top-navbar">
    <button class="btn btn-light d-md-none shadow-sm" onclick="document.getElementById('sidebar').classList.toggle('active')"><i class="fa fa-bars"></i></button>
    <h4 class="mb-0 fw-bold text-dark">Data Reports & Search</h4>
</div>

<div class="main-content">

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="fw-bold small text-muted">Select Role</label>
                    <select id="roleSelect" class="form-select fw-bold bg-light">
                        <option value="Student">Students</option>
                        <option value="Teacher">Teachers</option>
                        <option value="Staff">Staff</option>
                    </select>
                </div>
                
                <div class="col-md-3" id="batchDiv">
                    <label class="fw-bold small text-muted">Filter by Batch</label>
                    <select id="batchSelect" class="form-select bg-light">
                        <option value="">All Batches</option>
                        <?php
                        $b = $conn->query("SELECT * FROM Batches");
                        while($row = $b->fetch_assoc()) echo "<option value='".$row['batch_id']."'>".$row['batch_name']."</option>";
                        ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold small text-muted">Search (Name/ID)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-muted"></i></span>
                        <input type="text" id="liveSearch" class="form-control bg-light border-start-0" placeholder="Start typing to search...">
                    </div>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-success w-100 fw-bold shadow-sm" onclick="openExportModal('bulk')">
                        <i class="fa fa-file-export me-2"></i> Bulk Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Name / ID</th>
                        <th>Detail 1</th>
                        <th>Detail 2</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fa fa-sliders-h me-2"></i> Select Data to Export</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="print_report.php" method="POST" target="_blank">
                <div class="modal-body p-4">
                    
                    <input type="hidden" name="role" id="modal_role">
                    <input type="hidden" name="target_id" id="modal_id">
                    <input type="hidden" name="batch_id" id="modal_batch">

                    <p class="text-muted border-bottom pb-2">Check the boxes for the information you want to include in the report.</p>

                    <div id="field_checkboxes" class="row g-3"></div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="fa fa-print me-2"></i> Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// --- LIVE SEARCH LOGIC ---
const roleSelect = document.getElementById('roleSelect');
const batchSelect = document.getElementById('batchSelect');
const searchInput = document.getElementById('liveSearch');
const tableBody = document.getElementById('tableBody');
const batchDiv = document.getElementById('batchDiv');

function loadData() {
    let role = roleSelect.value;
    let q = searchInput.value;
    let batch = batchSelect.value;

    // Hide Batch select if not Student
    if(role !== 'Student') batchDiv.style.display = 'none';
    else batchDiv.style.display = 'block';

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "get_users.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        tableBody.innerHTML = this.responseText;
    }
    xhr.send("role=" + role + "&q=" + q + "&batch=" + batch);
}

// Events (Type, Change, etc.)
searchInput.addEventListener('input', loadData); // Triggers on typing & deleting
roleSelect.addEventListener('change', function(){
    searchInput.value = ''; // Clean search on role change
    loadData();
});
batchSelect.addEventListener('change', loadData);

// Initial Load
loadData();


// --- MODAL & ATTRIBUTES LOGIC ---
function openExportModal(type, id = null) {
    let role = roleSelect.value;
    let batch = batchSelect.value;
    
    // Set Hidden Inputs
    document.getElementById('modal_role').value = role;
    document.getElementById('modal_batch').value = batch;
    
    if(type === 'bulk') document.getElementById('modal_id').value = 'bulk';
    else document.getElementById('modal_id').value = id;

    let container = document.getElementById('field_checkboxes');
    container.innerHTML = ''; 

    // ALL POSSIBLE ATTRIBUTES
    let fields = [];
    if(role === 'Student') {
        fields = [
            {name:'roll_no', label:'Roll No', checked:true},
            {name:'first_name', label:'First Name', checked:true},
            {name:'surname', label:'Surname', checked:true},
            {name:'batch_name', label:'Batch', checked:true},
            {name:'father_name', label:'Father Name', checked:true},
            {name:'cnic', label:'CNIC / B-Form', checked:false},
            {name:'phone_number', label:'Phone Number', checked:false},
            {name:'email', label:'Email Address', checked:false},
            {name:'address', label:'Home Address', checked:false},
            {name:'gender', label:'Gender', checked:true},
            {name:'dob', label:'Date of Birth', checked:false}
        ];
    } else if(role === 'Teacher') {
        fields = [
            {name:'employee_id', label:'Employee ID', checked:true},
            {name:'first_name', label:'First Name', checked:true},
            {name:'last_name', label:'Last Name', checked:true},
            {name:'designation', label:'Designation', checked:true},
            {name:'email', label:'Email', checked:true},
            {name:'phone', label:'Phone', checked:false},
            {name:'gender', label:'Gender', checked:false},
            {name:'qualification', label:'Qualification', checked:false},
            {name:'salary', label:'Salary (Confidential)', checked:false},
            {name:'att_stats', label:'Attendance Stats', checked:true}
        ];
    } else if(role === 'Staff') {
        fields = [
            {name:'full_name', label:'Full Name', checked:true},
            {name:'designation', label:'Designation', checked:true},
            {name:'phone', label:'Phone', checked:true},
            {name:'join_date', label:'Joining Date', checked:false},
            {name:'salary', label:'Salary (Confidential)', checked:false}
        ];
    }

    // Render Checkboxes
    fields.forEach(f => {
        let isChecked = f.checked ? 'checked' : '';
        let html = `
            <div class="col-md-6 col-lg-4">
                <div class="form-check p-3 border rounded shadow-sm bg-light">
                    <input class="form-check-input" type="checkbox" name="fields[]" value="${f.name}" id="chk_${f.name}" ${isChecked}>
                    <label class="form-check-label fw-bold" for="chk_${f.name}">${f.label}</label>
                </div>
            </div>`;
        container.innerHTML += html;
    });

    new bootstrap.Modal(document.getElementById('exportModal')).show();
}
</script>

</body>
</html>