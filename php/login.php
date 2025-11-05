<?php
// login.php
session_start();

// config database
$host = '127.0.0.1';
$db   = 'vulnerable_app';
$user = 'root';
$pass = 'your_db_password';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF token optional (see note)
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $username = trim($username);

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);

        // prepared statement to avoid SQL injection
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $userRow = $stmt->fetch();

        if ($userRow) {
            $hash = $userRow['password'];
            if (password_verify($password, $hash)) {
                // success
                session_regenerate_id(true);
                $_SESSION['user_id'] = $userRow['id'];
                $_SESSION['username'] = $userRow['username'];
                $_SESSION['is_admin'] = ($userRow['username'] === 'admin'); 
                echo "Login successful. Hello " . htmlspecialchars($userRow['username']);
                // redirect to protected page
                // header("Location: admin_panel.php"); exit;
            } else {
                // invalid
                echo "Invalid credentials.";
            }
        } else {
            echo "Invalid credentials.";
        }
    } catch (Exception $e) {
        error_log("DB error login: " . $e->getMessage());
        echo "Internal server error.";
    }
    exit;
}

// GET -> show simple form
?>
<!doctype html>
<html><body>
  <h2>Login</h2>
  <form method="POST" action="">
    <label>Username: <input name="username" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
  </form>
</body></html>