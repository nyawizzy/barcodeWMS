<?php
session_start();

// Ensure the user is logged in and is a System Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'System Admin') {
    header("Location: loginpage.php");
    exit();
}

require 'db_connect.php';

// Function to get system metrics (example)
function getSystemMetrics($conn) {
    // Example metrics
    $metrics = [
        'uptime' => '99.99%', // This can be fetched from a system monitoring tool
        'total_users' => 0,
        'active_sessions' => 0
    ];

    // Fetch total users from the database
    $sql = "SELECT COUNT(*) AS total_users FROM users";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $metrics['total_users'] = $row['total_users'];
    }

    // Fetch active sessions from the database
    $sql = "SELECT COUNT(*) AS active_sessions FROM sessions WHERE status='active'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $metrics['active_sessions'] = $row['active_sessions'];
    }

    return $metrics;
}

$metrics = getSystemMetrics($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Overview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 50px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #800000;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            color: #fff;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .metric {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #ffc300;
            color: #800000;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>System Overview</h1>
    <div class="container">
        <div class="metric">
            <h2>System Uptime</h2>
            <p><?php echo $metrics['uptime']; ?></p>
        </div>
        <div class="metric">
            <h2>Total Users</h2>
            <p><?php echo $metrics['total_users']; ?></p>
        </div>
        <div class="metric">
            <h2>Active Sessions</h2>
            <p><?php echo $metrics['active_sessions']; ?></p>
        </div>
    </div>
</body>
</html>
