<?php
$conn = mysqli_connect("localhost:3307", "root", "");
if (!$conn) {
    die("❌ MySQL not connected - " . mysqli_connect_error());
}
echo "✅ MySQL connected on port 3307<br>";

$sql = "SHOW DATABASES";
$res = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($res)) {
    echo $row['Database'] . "<br>";
}
?>