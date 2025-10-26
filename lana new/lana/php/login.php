<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "vulnerable_app";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $sql);

echo "<!DOCTYPE html><html><body style='background-color:#1a1a1a;color:#d1c4e9;font-family:Arial;'>";
if (mysqli_num_rows($result) > 0) {
    echo "<h2>Welcome, $username!</h2>";
} else {
    echo "<h2>Invalid credentials</h2>";
}
echo "</body></html>";

mysqli_close($conn);
?>