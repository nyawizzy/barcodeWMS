<?php
session_start();

// Ensure the user is logged in and is a System Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'System Admin') {
    header("Location: loginpage.php");
    exit();
}

require 'db_connect.php';

// Fetch security logs
function getSecurityLogs($conn) {
    $sql = "SELECT * FROM logs ORDER BY timestamp DESC";
    $result = $conn->query($sql);
    $logs = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }
    }
    return $logs;
}

$logs = getSecurityLogs($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #800000;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            color: #fff;
        }

        .table th, .table td {
            color: #ffc300;
        }

        .btn {
            background-color: #ffc300;
            color: #800000;
            border: none;
        }

        .btn:hover {
            background-color: #e0b000;
        }

        .form-control {
            background-color: #f2f2f2;
            border: none;
            color: #800000;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #ffc300;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Security Logs</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>User ID</th>
                    <th>Action</th>
                    <th>Timestamp</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['log_id']) ?></td>
                        <td><?= htmlspecialchars($log['user_id']) ?></td>
                        <td><?= htmlspecialchars($log['action']) ?></td>
                        <td><?= htmlspecialchars($log['timestamp']) ?></td>
                        <td><?= htmlspecialchars($log['details']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>


