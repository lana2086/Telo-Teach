<?php
$admin_user = $_POST['admin_user'];
$admin_pass = $_POST['admin_pass'];

echo "<!DOCTYPE html><html><body style='background-color:#1a1a1a;color:#d1c4e9;font-family:Arial;'>";
if ($admin_user === 'admin' && $admin_pass === 'admin123') {
    echo "<h2>Welcome Admin!</h2>";
} else {
    echo "<h2>Invalid admin credentials.</h2>";
}
echo "</body></html>";
?>