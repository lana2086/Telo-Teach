<?php
// admin.php 
session_start();


$config = require _DIR_ . '/config.php';


if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    // ok
} else {
}


header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

function record_failed_attempt() {
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = [];
    }
    $_SESSION['login_attempts'][] = time();
    // keep only last 100 attempts to avoid session growth
    $_SESSION['login_attempts'] = array_slice($_SESSION['login_attempts'], -100);
}

function is_locked_out($max_attempts, $minutes) {
    if (empty($_SESSION['login_attempts'])) return false;
    $cutoff = time() - ($minutes * 60);
    $recent = array_filter($_SESSION['login_attempts'], function($t) use ($cutoff) { return $t >= $cutoff; });
    return count($recent) >= $max_attempts;
}

if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    echo "<!doctype html><html><body><h2>Welcome Admin (already logged in)</h2></body></html>";
    exit;
}


if (is_locked_out($config['max_attempts'], $config['lock_minutes'])) {
    http_response_code(429); // Too Many Requests
    echo "<!doctype html><html><body><h2>Too many login attempts. Please try again later.</h2></body></html>";
    exit;
}

// 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_user = $_POST['admin_user'] ?? '';
    $admin_pass = $_POST['admin_pass'] ?? '';

   
    $admin_user = trim($admin_user);
   
    $valid_user = 'admin';

    if (hash_equals($valid_user, $admin_user) && password_verify($admin_pass, $config['admin_hash'])) {
       
        session_regenerate_id(true); 
        $_SESSION['is_admin'] = true;
        
        echo "<!doctype html><html><body><h2>Welcome Admin!</h2></body></html>";
        exit;
    } else {
        // فشل - سجّل محاولة
        record_failed_attempt();
        echo "<!doctype html><html><body><h2>Invalid credentials.</h2></body></html>";
        exit;
    }
}


$token = bin2hex(random_bytes(16));
$_SESSION['csrf_token'] = $token;
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin login</title></head>
<body>
  <form method="POST" action="">
    <label>Username: <input name="admin_user" required></label><br>
    <label>Password: <input type="password" name="admin_pass" required></label><br>
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token, ENT_QUOTES); ?>">
    <button type="submit">Login</button>
  </form>
</body>
</html>