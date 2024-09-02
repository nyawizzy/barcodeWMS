<?php
// log.php
include 'db_connection.php';

function logEvent($userId, $action, $details = '') {
    $db = getDbConnection();

    try {
        $stmt = $db->prepare("INSERT INTO logs (user_id, action, details, timestamp) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$userId, $action, $details]);
    } catch (PDOException $e) {
        // If even logging the error fails, handle it accordingly (e.g., log to a file or notify admin)
        error_log("Error logging event: " . $e->getMessage());
    }
}

// Example usage
// Logging a user activity
logEvent(1, 'User login', 'User logged in successfully.');

// Logging an error
logEvent(null, 'Error', 'Sample error message occurred in file.php on line 123.');
?>
