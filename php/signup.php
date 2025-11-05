
<?php
// signup.php - secure version (mysqli + prepared statements + password hashing)

$DB_HOST = 'localhost';
$DB_USER = 'vul_app_user';   
$DB_PASS = 'change_this_password';
$DB_NAME = 'vulnerable_app';

// create mysqli connection (use error logging, not echo)
$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn) {
    error_log("DB connection failed: " . mysqli_connect_error());
    http_response_code(500);
    echo "<h2>Server error. Try again later.</h2>";
    exit;
}

// Read raw inputs
$name     = isset($_POST['name']) ? trim($_POST['name']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Basic validation
$errors = [];
if ($name === '' || mb_strlen($name) > 100) $errors[] = "Invalid name";
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 200) $errors[] = "Invalid email";
if (mb_strlen($password) < 8) $errors[] = "Password must be at least 8 characters";

if (!empty($errors)) {
    http_response_code(400);
    echo "<h2>Input error</h2><ul>";
    foreach ($errors as $e) {
        echo '<li>' . htmlspecialchars($e, ENT_QUOTES, 'UTF-8') . '</li>';
    }
    echo "</ul>";
    exit;
}

// Check if user exists
$stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    echo "<h2>Account with that email already exists.</h2>";
    exit;
}
mysqli_stmt_close($stmt);

// Hash password (bcrypt)
$hash = password_hash($password, PASSWORD_DEFAULT);

// Insert user with prepared statement
$insert = mysqli_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)");
mysqli_stmt_bind_param($insert, "ss", $email, $hash);
$ok = mysqli_stmt_execute($insert);
if ($ok) {
    echo "<h2>Account created successfully for " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ".</h2>";
} else {
    error_log("DB insert error: " . mysqli_error($conn));
    http_response_code(500);
    echo "<h2>Server error - could not create account.</h2>";
}
mysqli_stmt_close($insert);
mysqli_close($conn);
?>
