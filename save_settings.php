<?php
require 'db_connect.php';

$timezone = $_POST['timezone'];
$currency_format = $_POST['currency_format'];
$low_stock_alert = $_POST['low_stock_alert'];

$sql = "UPDATE settings SET setting_value='$timezone' WHERE setting_name='timezone';
        UPDATE settings SET setting_value='$currency_format' WHERE setting_name='currency_format';
        UPDATE settings SET setting_value='$low_stock_alert' WHERE setting_name='low_stock_alert'";

$response = [];
if ($conn->multi_query($sql) === TRUE) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $conn->error;
}

echo json_encode($response);

$conn->close();
?>
