<?php
session_start();

// Ensure the user is logged in and is a System Admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'Staff'&& $_SESSION['role'] != 'Manager')) {
    header("Location: loginpage.php");
    exit();
}

require 'db_connect.php';

// Function to handle support requests (example)
function submitSupportRequest($conn, $message) {
    $userId = $_SESSION['user_id'];
    $sql = "INSERT INTO support_requests (user_id, message, status) VALUES ('$userId', '$message', 'open')";
    if ($conn->query($sql) === TRUE) {
        return "Support request submitted successfully.";
    } else {
        return "Error submitting support request: " . $conn->error;
    }
}

// Handle support actions
$response = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['message'])) {
        $message = $_POST['message'];
        $response = submitSupportRequest($conn, $message);
    }
}

// Function to fetch support requests (example)
function getSupportRequests($conn) {
    $requests = [];
    $sql = "SELECT * FROM support_requests ORDER BY created_at DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
    }
    return $requests;
}

$supportRequests = getSupportRequests($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support</title>
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

        .support-request {
            background-color: #fff;
            color: #000;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Support</h1>
    <div class="container">
        <?php if (!empty($response)): ?>
            <div class="message"><?php echo $response; ?></div>
        <?php endif; ?>
        <form method="POST" action="support.php">
            <textarea name="message" rows="5" placeholder="Describe your issue here..." required></textarea>
            <button type="submit" class="button">Submit Support Request</button>
        </form>
        <h2>Support Requests</h2>
        <?php foreach ($supportRequests as $request): ?>
            <div class="support-request">
                <strong><?php echo $request['created_at']; ?>:</strong>
                <p><?php echo $request['message']; ?></p>
                <p>Status: <?php echo $request['status']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>

