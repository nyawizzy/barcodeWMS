<?php
// config.php
$servername = "localhost";
$username = "root";
$password = "Munga@123";
$dbname = "wms";

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
