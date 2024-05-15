<?php
// Database connection parameters
        $servername = "hrtab.ckmky0tsprxi.us-east-1.rds.amazonaws.com";
        $username = "admin";
        $password = "adminadmin123";
        $database = "stock_predict";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
