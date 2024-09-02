<?php
require 'db_connect.php';

// Fetch system status
$systemStatus = [
    'server_status' => 'Online',
    'database_status' => $conn->ping() ? 'Online' : 'Offline'
];

// Fetch recent activities 
$recentActivities = [];
$activitySql = "SELECT action, details, timestamp FROM logs ORDER BY timestamp DESC LIMIT 5";
$activityResult = $conn->query($activitySql);
if ($activityResult->num_rows > 0) {
    while($row = $activityResult->fetch_assoc()) {
        $recentActivities[] = $row;
    }
}

// Fetch key metrics
$metrics = [
    'total_products' => 0,
    'total_users' => 0,
    'inventory_value' => 0
];

// Total products
$productCountSql = "SELECT COUNT(*) as count FROM products";
$productCountResult = $conn->query($productCountSql);
if ($productCountResult->num_rows > 0) {
    $metrics['total_products'] = $productCountResult->fetch_assoc()['count'];
}

// Total users
$userCountSql = "SELECT COUNT(*) as count FROM users";
$userCountResult = $conn->query($userCountSql);
if ($userCountResult->num_rows > 0) {
    $metrics['total_users'] = $userCountResult->fetch_assoc()['count'];
}

// Inventory value 
$inventoryValueSql = "SELECT SUM(price * quantity) as total_value FROM products";
$inventoryValueResult = $conn->query($inventoryValueSql);
if ($inventoryValueResult->num_rows > 0) {
    $metrics['inventory_value'] = $inventoryValueResult->fetch_assoc()['total_value'];
}

$conn->close();

// Return all data as JSON
echo json_encode([
    'system_status' => $systemStatus,
    'recent_activities' => $recentActivities,
    'key_metrics' => $metrics
]);
?>

