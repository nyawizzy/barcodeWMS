<?php
session_start();

// Ensure the user is logged in and is a System Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'System Admin') {
    header("Location: loginpage.php");
    exit();
}

require 'db_connect.php';

// Function to clear cache (example)
function clearCache() {
    // Example logic to clear cache
    // You would replace this with actual cache clearing logic
    return "Cache cleared successfully.";
}

// Function to run backup (example)
function runBackup() {
    // Example logic to run backup
    // You would replace this with actual backup logic
    return "Backup completed successfully.";
}

// Handle maintenance actions
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'clear_cache') {
        $message = clearCache();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'run_backup') {
        $message = runBackup();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Maintenance</title>
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
        
        .button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #ffc300;
            color: #800000;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            margin-bottom: 20px;
        }
        
        .button:hover {
            background-color: #e0b000;
        }
        
        .message {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #ffc300;
            color: #800000;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>System Maintenance</h1>
    <div class="container">
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="system_maintenance.php">
            <input type="hidden" name="action" value="clear_cache">
            <button type="submit" class="button">Clear Cache</button>
        </form>
        <form method="POST" action="system_maintenance.php">
            <input type="hidden" name="action" value="run_backup">
            <button type="submit" class="button">Run Backup</button>
        </form>
    </div>
</body>
</html>

