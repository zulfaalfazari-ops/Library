<?php
$host = "localhost:3307";
$user = "root";
$pass = "";
$db   = "library_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
