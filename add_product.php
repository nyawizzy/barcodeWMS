<?php
require 'db_connect.php';

$product_name = $_POST['product_name'];
$description = $_POST['description'];
$barcode = $_POST['barcode'];
$quantity = $_POST['quantity'];
$location = $_POST['location'];
$price = $_POST['price'];

$sql = "INSERT INTO products (product_name, description, barcode, quantity, location, price) VALUES ('$product_name', '$description', '$barcode', '$quantity', '$location', '$price')";
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

