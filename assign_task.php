<?php
session_start();

// Ensure the user is logged in and is a Manager
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Manager') {
    header("Location: loginpage.php");
    exit();
}

require 'db_connect.php';

// Fetch staff members for assignment
$sql = "SELECT user_id, full_name FROM users WHERE role = 'Staff'";
$result = $conn->query($sql);
$staffMembers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $staffMembers[] = $row;
    }
}

// Handle task assignment form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taskDescription = $_POST['task_description'];
    $assignedTo = $_POST['assigned_to'];

    $sql = "INSERT INTO tasks (task_description, assigned_to, status) VALUES (?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $taskDescription, $assignedTo);
    if ($stmt->execute()) {
        $message = "Task assigned successfully!";
    } else {
        $message = "Error assigning task: " . $stmt->error;
    }
}

// Fetch all tasks for display
$sql = "SELECT t.task_id, t.task_description, t.status, u.full_name 
        FROM tasks t 
        JOIN users u ON t.assigned_to = u.user_id 
        ORDER BY t.created_at DESC";
$result = $conn->query($sql);
$tasks = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Task</title>
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
            color: #ffc300;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        textarea, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: none;
        }

        button {
            background-color: #ffc300;
            color: #800000;
            cursor: pointer;
        }

        button:hover {
            background-color: #e0b000;
        }

        .task-list {
            margin-top: 20px;
        }

        .task {
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            color: #800000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Assign Task</h1>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST" action="assign_task.php">
            <label for="task_description">Task Description:</label>
            <textarea id="task_description" name="task_description" required></textarea><br>
            
            <label for="assigned_to">Assign to:</label>
            <select id="assigned_to" name="assigned_to" required>
                <?php foreach ($staffMembers as $staff): ?>
                    <option value="<?php echo $staff['user_id']; ?>"><?php echo $staff['full_name']; ?></option>
                <?php endforeach; ?>
            </select><br>
            
            <button type="submit">Assign Task</button>
        </form>

        <div class="task-list">
            <h2>Assigned Tasks</h2>
            <?php foreach ($tasks as $task): ?>
            <div class="task">
                <strong><?php echo htmlspecialchars($task['full_name']); ?>:</strong>
                <p><?php echo htmlspecialchars($task['task_description']); ?></p>
                <p>Status: <?php echo htmlspecialchars($task['status']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>


