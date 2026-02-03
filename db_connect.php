<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SWE_DEPARTMENT"; // <--- Yahan apna sahi DB Name likhein

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- SYSTEM LOGS FUNCTION (Bug Free) ---
if (!function_exists('log_activity')) {
    function log_activity($user_id, $role, $action, $description) {
        global $conn;
        
        // Agar connection lost ho jaye to reconnect (Safety)
        if ($conn->ping() === false) {
             $conn = new mysqli("localhost", "root", "", $GLOBALS['dbname']);
        }

        $ip_address = $_SERVER['REMOTE_ADDR'];
        
        // Prepare Statement (SQL Injection Proof)
        $stmt = $conn->prepare("INSERT INTO system_logs (user_id, role, action, description, ip_address) VALUES (?, ?, ?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("issss", $user_id, $role, $action, $description, $ip_address);
            $stmt->execute();
            $stmt->close();
        }
    }
}
?>