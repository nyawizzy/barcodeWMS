<?php
session_start();

// Ensure the user is logged in and is a Staff member
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Staff') {
    header("Location: loginpage.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 50px;
        }

        h1 {
            text-align: center;
            margin-bottom: 50px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #800000;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            color: #fff;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 20px;
        }

        a {
            display: block;
            background-color: #ffc300;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
        }

        a:hover {
            background-color: #e0b000;
        }
    </style>
</head>
<body>
    <h1>Staff Dashboard</h1>
    <div class="container">
        <ul>
            <li><a href="task_overview.php">Task Overview</a></li>
            
            <li><a href="inventory_management.php">Inventory Management</a></li>
            <li><a href="support.php">Support</a></li>
        </ul>
    </div>
</body>
</html>

