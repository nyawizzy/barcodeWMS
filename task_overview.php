<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Staff') {
    header("Location: loginpage.php");
    exit();
}

require 'db_connect.php';

// Fetch tasks assigned to the logged-in staff member
$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM tasks WHERE assigned_to = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$tasks = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}

// Handle task status update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taskId = $_POST['task_id'];
    $newStatus = $_POST['status'];

    $sql = "UPDATE tasks SET status = ? WHERE task_id = ? AND assigned_to = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $newStatus, $taskId, $userId);
    if ($stmt->execute()) {
        $message = "Task status updated successfully!";
    } else {
        $message = "Error updating task status: " . $stmt->error;
    }

    // Refresh the page to show the updated tasks
    header("Location: task_overview.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Overview</title>
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

        form {
            display: inline;
        }

        select, button {
            padding: 5px;
            margin-left: 10px;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Task Overview</h1>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <ul class="task-list">
            <?php foreach ($tasks as $task): ?>
            <li class="task">
                <p><?php echo htmlspecialchars($task['task_description']); ?></p>
                <p>Status: <?php echo htmlspecialchars($task['status']); ?></p>
                <form method="POST" action="task_overview.php">
                    <input type="hidden" name="task_id" value="<?php echo $task['task_id']; ?>">
                    <select name="status">
                        <option value="Pending" <?php echo $task['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="In Progress" <?php echo $task['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Completed" <?php echo $task['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                    </select>
                    <button type="submit">Update Status</button>
                </form>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
