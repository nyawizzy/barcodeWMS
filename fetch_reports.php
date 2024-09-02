<?php
require 'db_connect.php';

// Fetch reports
$sql = "SELECT * FROM reports";
$result = $conn->query($sql);

$reports = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
} else {
    $reports = array();
}

echo json_encode($reports);

$conn->close();
?>

