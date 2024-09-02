<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $barcode = $data['barcode'];

    if (!empty($barcode)) {
        // Check if the barcode already exists in the database
        $stmt = $conn->prepare("SELECT * FROM products WHERE barcode = ?");
        $stmt->bind_param("s", $barcode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Barcode exists, return the product details
            $product = $result->fetch_assoc();
            $response = [
                'status' => 'success',
                'message' => 'Barcode found',
                'barcode' => $product['barcode'],
                'name' => $product['product_name'],
                'description' => $product['description'],
                'quantity' => $product['quantity'],
                'location' => $product['location'],
                'price' => $product['price']
            ];
        } else {
            // Barcode does not exist, add a new product
            $name = "Product-" . strtoupper(uniqid());
            $description = "New Product Description";
            $quantity = 0; // default value
            $location = "Unknown Location";
            $price = 0.00; // default value

            $stmt = $conn->prepare("INSERT INTO products (product_name, description, barcode, quantity, location, price) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiis", $name, $description, $barcode, $quantity, $location, $price);
            if ($stmt->execute()) {
                $response = [
                    'status' => 'success',
                    'message' => 'New product added',
                    'barcode' => $barcode,
                    'name' => $name,
                    'description' => $description,
                    'quantity' => $quantity,
                    'location' => $location,
                    'price' => $price
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Error adding product: ' . $conn->error
                ];
            }
        }

        echo json_encode($response);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Barcode cannot be empty.']);
    }
}
?>

