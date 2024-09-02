<?php
require 'db_connect.php';

$id = $_POST['id'];

$sql = "DELETE FROM products WHERE product_id=$id";
$response = [];
if ($conn->query($sql) === TRUE) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $conn->error;
}

echo json_encode($response);

$conn->close();
?>
