<?php
include '../db_connect.php'; // Database connection

$msg = "";

if (isset($_POST['upload_update'])) {
    $filename = $_FILES["file"]["tmp_name"];

    if ($_FILES["file"]["size"] > 0) {
        $file = fopen($filename, "r");
        
        // Header row skip karne ke liye (Agar CSV me headers hain)
        fgetcsv($file); 

        $count = 0;
        while (($data = fgetcsv($file, 10000, ",")) !== FALSE) {
            
            // CSV Columns ki mapping (0=RollNo, 1=CNIC, 2=DOB)
            $roll_no = $conn->real_escape_string($data[0]);
            $cnic    = $conn->real_escape_string($data[1]);

            // UPDATE Query (Sirf CNIC aur DOB change karega)
            $sql = "UPDATE Students SET cnic = '$cnic' WHERE roll_no = '$roll_no'";
            
            if ($conn->query($sql)) {
                $count++;
            }
        }
        fclose($file);
        $msg = "<div class='alert alert-success'>Successfully Updated $count Records!</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bulk Update CNIC & DOB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5 bg-light">
    <div class="container">
        <div class="card shadow p-4 mx-auto" style="max-width: 600px;">
            <h3 class="text-center text-primary mb-3">Update Student Data (CNIC & DOB)</h3>
            <?php echo $msg; ?>
            
            <div class="alert alert-info small">
                <strong>Format:</strong> CSV File honi chahiye.<br>
                <strong>Columns:</strong> Roll No, CNIC, DOB (YYYY-MM-DD)<br>
                <strong>Note:</strong> Ye naye students add nahi karega, purano ko fix karega.
            </div>

            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Select CSV File</label>
                    <input type="file" name="file" class="form-control" required accept=".csv">
                </div>
                <button type="submit" name="upload_update" class="btn btn-warning w-100 fw-bold">Update Data Now</button>
            </form>
        </div>
    </div>
</body>
</html>