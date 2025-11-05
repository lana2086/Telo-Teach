<?php
// upload_safe.php
// Safer upload handler example

// Config
$MAX_FILE_BYTES = 2 * 1024 * 1024; // 2 MB
$ALLOWED_EXT = ['jpg','jpeg','png','gif','txt','pdf']; 
$ALLOWED_MIME = ['image/jpeg','image/png','image/gif','text/plain','application/pdf'];

// Target directory - OUTSIDE webroot is preferred.
// Example relative: move one level up from web root into ../uploads_storage
$target_base = realpath(_DIR_ . '/../uploads_storage'); // <-- ensure exists
if ($target_base === false) {
    http_response_code(500);
    error_log("Upload handler: uploads_storage not found");
    echo "<h2>Server configuration error.</h2>";
    exit;
}

if (!isset($_FILES['file'])) {
    http_response_code(400);
    echo "<h2>No file uploaded.</h2>";
    exit;
}

$file = $_FILES['file'];
$tmp  = $file['tmp_name'];
$orig = $file['name'];
$size = $file['size'];
$err  = $file['error'];

// basic checks
if ($err !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo "<h2>Upload error (code: $err).</h2>";
    exit;
}
if ($size <= 0 || $size > $MAX_FILE_BYTES) {
    http_response_code(400);
    echo "<h2>File too large or empty.</h2>";
    exit;
}

// extension check (from original name)
$ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
if (!in_array($ext, $ALLOWED_EXT, true)) {
    http_response_code(400);
    echo "<h2>File type not allowed (ext).</h2>";
    exit;
}

// MIME type check using finfo (more reliable than $_FILES['type'])
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $tmp);
finfo_close($finfo);

if (!in_array($mime, $ALLOWED_MIME, true)) {
    http_response_code(400);
    echo "<h2>File type not allowed (mime): " . htmlspecialchars($mime) . "</h2>";
    exit;
}

// Extra check for images: use getimagesize (prevents fake images)
if (strpos($mime, 'image/') === 0) {
    $imginfo = @getimagesize($tmp);
    if ($imginfo === false) {
        http_response_code(400);
        echo "<h2>Invalid image file.</h2>";
        exit;
    }
}

// Create a random filename to avoid collisions and info disclosure
$random = bin2hex(random_bytes(16)); // 32 hex chars
$target_name = $random . '.' . $ext;
$target_path = $target_base . DIRECTORY_SEPARATOR . $target_name;

// Move uploaded file securely
if (!move_uploaded_file($tmp, $target_path)) {
    error_log("Failed move_uploaded_file to $target_path");
    http_response_code(500);
    echo "<h2>Server error saving file.</h2>";
    exit;
}

// Set safe file permissions (owner read/write only)
chmod($target_path, 0600);

// Logging (optional)
error_log("Upload saved: $target_name size=$size mime=$mime");

// Success output (do NOT reveal server paths)
echo "<h2>Upload successful</h2>";
echo "<p>Stored as: " . htmlspecialchars($target_name, ENT_QUOTES, 'UTF-8') . "</p>";
?>