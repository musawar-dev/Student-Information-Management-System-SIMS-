<?php
session_start();
include '../db_connect.php';
$q = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <a href="dashboard.php" class="btn btn-secondary mb-3">Back</a>
        <h3>Search: "<?php echo $q; ?>"</h3>
        <div class="list-group">
            <?php
            if(strlen($q) > 1){
                $res = $conn->query("SELECT * FROM Students WHERE first_name LIKE '%$q%' OR roll_no LIKE '%$q%'");
                while($row = $res->fetch_assoc()){
                    echo "<div class='list-group-item'>".$row['first_name']." ".$row['surname']." (".$row['roll_no'].")</div>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>