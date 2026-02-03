<?php
session_start();
include '../db_connect.php';

// Security Check
if (!isset($_SESSION['user_id'])) { header("Location: ../login.php"); exit(); }

$q = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body{ background:#f5f7fa; padding: 30px; font-family: 'Segoe UI', sans-serif; }
        .card { border:none; border-radius:15px; box-shadow:0 5px 15px rgba(0,0,0,0.05); transition:0.3s; }
        .card:hover { transform:translateY(-5px); }
    </style>
</head>
<body>

<div class="container" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="fa fa-search me-2 text-primary"></i> Search Results</h3>
        <a href="dashboard.php" class="btn btn-secondary rounded-pill px-4"><i class="fa fa-arrow-left me-2"></i> Back</a>
    </div>

    <p class="text-muted">Showing results for: <strong>"<?php echo htmlspecialchars($q); ?>"</strong></p>

    <?php if(strlen($q) < 2): ?>
        <div class="alert alert-warning">Please enter at least 2 characters to search.</div>
    <?php else: ?>

        <div class="row g-4">
            
            <?php
            $s_sql = "SELECT * FROM Students WHERE first_name LIKE '%$q%' OR roll_no LIKE '%$q%' OR surname LIKE '%$q%'";
            $s_res = $conn->query($s_sql);
            if($s_res->num_rows > 0){
                while($row = $s_res->fetch_assoc()){
                    echo '<div class="col-md-12">
                        <div class="card p-3 border-start border-4 border-primary">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="fw-bold mb-1">'.$row['first_name'].' '.$row['surname'].' <span class="badge bg-primary text-white ms-2">Student</span></h5>
                                    <small class="text-muted">'.$row['roll_no'].' | Batch: '.$row['batch_id'].'</small>
                                </div>
                                <a href="edit_student.php?id='.$row['user_id'].'" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                    </div>';
                }
            }
            ?>

            <?php
            $t_sql = "SELECT * FROM Teachers WHERE first_name LIKE '%$q%' OR last_name LIKE '%$q%' OR designation LIKE '%$q%'";
            $t_res = $conn->query($t_sql);
            if($t_res->num_rows > 0){
                while($row = $t_res->fetch_assoc()){
                    echo '<div class="col-md-12">
                        <div class="card p-3 border-start border-4 border-success">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="fw-bold mb-1">'.$row['first_name'].' '.$row['last_name'].' <span class="badge bg-success text-white ms-2">Teacher</span></h5>
                                    <small class="text-muted">'.$row['designation'].' | '.$row['email'].'</small>
                                </div>
                                <a href="edit_teacher.php?id='.$row['user_id'].'" class="btn btn-sm btn-outline-success">View</a>
                            </div>
                        </div>
                    </div>';
                }
            }
            ?>

            <?php
            $c_sql = "SELECT * FROM Courses WHERE course_title LIKE '%$q%' OR course_code LIKE '%$q%'";
            $c_res = $conn->query($c_sql);
            if($c_res->num_rows > 0){
                while($row = $c_res->fetch_assoc()){
                    echo '<div class="col-md-12">
                        <div class="card p-3 border-start border-4 border-warning">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="fw-bold mb-1">'.$row['course_title'].' <span class="badge bg-warning text-dark ms-2">Course</span></h5>
                                    <small class="text-muted">Code: '.$row['course_code'].' | Semester: '.$row['semester'].'</small>
                                </div>
                                <a href="manage_courses.php" class="btn btn-sm btn-outline-warning">View</a>
                            </div>
                        </div>
                    </div>';
                }
            }
            ?>
            
            <?php 
            // Agar teeno tables me kuch na mile
            if($s_res->num_rows == 0 && $t_res->num_rows == 0 && $c_res->num_rows == 0){
                echo '<div class="col-12"><div class="alert alert-light text-center p-5 shadow-sm"><h4><i class="fa fa-folder-open text-muted mb-3 d-block fa-2x"></i>No Results Found</h4></div></div>';
            }
            ?>
        </div>

    <?php endif; ?>
</div>

</body>
</html>