<?php
session_start();
include 'db_connect.php';

$error = "";

if(isset($_POST['login'])){
    $input = $conn->real_escape_string($_POST['email']); // User kuch bhi daal sakta hai (Email/RollNo/ID)
    $pass = $_POST['password'];

    // --- SMART LOGIN LOGIC ---
    
    // Step 1: Maan lete hain user ne EMAIL dali hai
    $sql = "SELECT * FROM Users WHERE email = '$input'";
    $result = $conn->query($sql);

    // Step 2: Agar Email nahi mili, to check karo kya ye STUDENT ROLL NO hai?
    if($result->num_rows == 0){
        $std_check = $conn->query("SELECT user_id FROM Students WHERE roll_no = '$input'");
        if($std_check->num_rows > 0){
            $u_id = $std_check->fetch_assoc()['user_id'];
            $sql = "SELECT * FROM Users WHERE user_id = '$u_id'";
            $result = $conn->query($sql);
        }
    }

    // Step 3: Agar wo bhi nahi, to check karo kya ye TEACHER EMPLOYEE ID hai?
    if($result->num_rows == 0){
        $tch_check = $conn->query("SELECT user_id FROM Teachers WHERE employee_id = '$input'");
        if($tch_check->num_rows > 0){
            $u_id = $tch_check->fetch_assoc()['user_id'];
            $sql = "SELECT * FROM Users WHERE user_id = '$u_id'";
            $result = $conn->query($sql);
        }
    }

    // Step 4: Agar wo bhi nahi, to check karo kya ye STAFF ID hai? (Optional)
    // (Staff usually login nahi karte, par agar future me zaroorat ho)

    // --- FINAL CHECK ---
    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        
        // Password Verify (Hash Check)
        if(password_verify($pass, $user['password'])){
            
            // Login Success! Session Set karo
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            // Redirect based on Role
            if($user['role'] == 'Admin' || $user['role'] == 'HOD'){
                header("Location: admin/dashboard.php");
            } elseif($user['role'] == 'Teacher'){
                header("Location: teacher/dashboard.php");
            } elseif($user['role'] == 'Clerk'){
                header("Location: clerk/dashboard.php");
            } elseif($user['role'] == 'Student'){
                header("Location: student/dashboard.php");
            }
            exit();

        } else {
            $error = "❌ Wrong Password!";
        }
    } else {
        $error = "❌ Wrong Username! (Check Roll_no or Email)";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - QUEST SWE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #717c8b 0%, #566375 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        .logo-img { width: 80px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="login-card text-center">
    <img src="uploads/logo.png" class="logo-img" alt="Logo">
    <h3 class="fw-bold text-dark mb-4">Welcome Back</h3>
    
    <?php if($error){ echo "<div class='alert alert-danger p-2 small'>$error</div>"; } ?>

    <form method="POST">
        <div class="mb-3 text-start">
            <label class="form-label fw-bold small text-muted">Email, Roll No or Emp ID</label>
            <input type="text" name="email" class="form-control form-control-lg bg-light" placeholder="e.g. 24SW05" required>
        </div>
        
        <div class="mb-4 text-start">
            <label class="form-label fw-bold small text-muted">Password</label>
            <input type="password" name="password" class="form-control form-control-lg bg-light" placeholder="Enter Password" required>
        </div>

        <button type="submit" name="login" class="btn btn-primary w-100 btn-lg fw-bold shadow">Login</button>
    </form>
    
    <div class="mt-4 text-muted small">
        Forgot Password? Contact Clerk Office.
    </div>
</div>

</body>
</html>