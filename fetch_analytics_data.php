<?php
require 'db_connect.php';

// Array to hold the data
$data = [];

// Fetch user activity data
$userActivityQuery = "SELECT DATE_FORMAT(logged_at, '%Y-%m-%d') AS date, COUNT(*) AS activity_count 
                      FROM user_logs 
                      GROUP BY date 
                      ORDER BY date DESC 
                      LIMIT 10";
$userActivityResult = $conn->query($userActivityQuery);

$userActivityData = ['labels' => [], 'values' => []];
if ($userActivityResult->num_rows > 0) {
    while($row = $userActivityResult->fetch_assoc()) {
        $userActivityData['labels'][] = $row['date'];
        $userActivityData['values'][] = $row['activity_count'];
    }
}
$data['userActivity'] = $userActivityData;

// Fetch product sales data
$productSalesQuery = "SELECT product_name, SUM(quantity_sold) AS total_sales 
                      FROM product_sales 
                      GROUP BY product_name 
                      ORDER BY total_sales DESC 
                      LIMIT 10";
$productSalesResult = $conn->query($productSalesQuery);

$productSalesData = ['labels' => [], 'values' => []];
if ($productSalesResult->num_rows > 0) {
    while($row = $productSalesResult->fetch_assoc()) {
        $productSalesData['labels'][] = $row['product_name'];
        $productSalesData['values'][] = $row['total_sales'];
    }
}
$data['productSales'] = $productSalesData;

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
