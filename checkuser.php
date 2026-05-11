<?php
session_start();

$servername = getenv('MYSQLHOST')     ?: 'localhost';
$username   = getenv('MYSQLUSER')     ?: 'root';
$password   = getenv('MYSQLPASSWORD') ?: '';
$dbname     = getenv('MYSQLDATABASE') ?: 'footwear';
$port       = getenv('MYSQLPORT')     ?: 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST["username"];
$password = $_POST["password"];

$sql  = "SELECT * FROM user WHERE BINARY username = ? AND BINARY password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION["logged_in"] = true;
    $_SESSION["username"]  = $username;
    echo "<script> alert('Access granted.');window.location.replace('view2.php');</script>";
} else {
    echo "<script> alert('Access denied.');window.location.replace('login.php');</script>";
}

$stmt->close();
$conn->close();
?>
