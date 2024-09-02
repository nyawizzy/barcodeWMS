<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "Munga@123";
$dbname = "wms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select all users and their plaintext passwords
$sql = "SELECT user_id, password FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through each user
    while($row = $result->fetch_assoc()) {
        $userId = $row['user_id'];
        $plaintextPassword = $row['password'];

        // Hash the plaintext password
        $hashedPassword = password_hash($plaintextPassword, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $updateSql = "UPDATE users SET password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("si", $hashedPassword, $userId);
        $stmt->execute();

        // Optional: Check if the update was successful
        if ($stmt->affected_rows === 1) {
            echo "Password for user ID $userId updated successfully.<br>";
        } else {
            echo "Failed to update password for user ID $userId.<br>";
        }

        $stmt->close();
    }
} else {
    echo "No users found.";
}

$conn->close();
?>
