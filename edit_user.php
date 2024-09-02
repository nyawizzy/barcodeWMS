<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $employee_no = $_POST['employee_no'];

    $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, role = ?, employee_no = ? WHERE user_id = ?");
    $stmt->bind_param("sssii", $username, $email, $role, $employee_no, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>




