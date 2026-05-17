<?php
$host = "localhost";
$username = "root";
$password = "as.masrafi2.k";  // change if your MySQL has a password
$database = "expense_tracker";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>
