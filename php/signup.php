
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "vulnerable_app";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// Insert user data into the database
$sql = "INSERT INTO users (username, password) VALUES ('$email', '$password')";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<h2>Account created successfully for $name!</h2>";
} else {
    echo "<h2>Error creating account.</h2>";
}

mysqli_close($conn);
?>
