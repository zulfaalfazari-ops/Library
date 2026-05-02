<?php
echo "Start<br>";
$conn = mysqli_connect("localhost", "root", "");
if (!$conn) {
    die("❌ MySQL no connection: " . mysqli_connect_error());
}
echo "✅ Connected to MySQL<br>";

$res = mysqli_query($conn, "SHOW DATABASES");
while ($row = mysqli_fetch_assoc($res)) {
    echo $row['Database'] . "<br>";
}
?>