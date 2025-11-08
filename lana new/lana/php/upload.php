<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);

if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
    echo "<!DOCTYPE html><html><body style='background-color:#1a1a1a;color:#d1c4e9;font-family:Arial;'>";
    echo "<h2>File uploaded: " . htmlspecialchars(basename($_FILES["file"]["name"])) . "</h2>";
    echo "</body></html>";
} else {
    echo "<!DOCTYPE html><html><body style='background-color:#1a1a1a;color:#d1c4e9;font-family:Arial;'>";
    echo "<h2>Error uploading file.</h2>";
    echo "</body></html>";
}
?>