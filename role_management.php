<?php
require 'db_connect.php';

$sql = "SELECT role_id, role_name FROM roles";
$result = $conn->query($sql);

if ($result === false) {
    die("Database query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['role_name']}</td>
                <td>Permissions Placeholder</td>
                <td>
                    <a href='#' class='btn btn-sm btn-primary'>Edit</a>
                    <a href='#' class='btn btn-sm btn-danger'>Delete</a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='3'>No roles found</td></tr>";
}

$conn->close();
?>


