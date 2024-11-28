<?php
$servername = "127.0.0.1"; // Database host
$username = "root"; // Database username
$password = ""; // Database password (default for XAMPP)
$dbname = "bookstore"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
