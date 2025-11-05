<?php
// search.php - safe version

// Read and sanitize input
$raw_query = filter_input(INPUT_GET, 'query', FILTER_UNSAFE_RAW);
if ($raw_query === null) {
    $raw_query = '';
}

// Trim and limit length to avoid huge payloads (مثال: 200 chars)
$query = mb_substr(trim($raw_query), 0, 200);

// Escape for HTML output (prevent XSS)
$escaped = htmlspecialchars($query, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

// Optional: build a safe page title
$safe_title = $escaped !== '' ? "Search results for: $escaped" : "Search";

// Output
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?php echo $safe_title; ?></title>
  <meta http-equiv="Content-Security-Policy" content="default-src 'self';">
  <style>
    body { background-color:#1a1a1a; color:#d1c4e9; font-family:Arial, sans-serif; padding:20px; }
    h2 { font-size:18px; }
  </style>
</head>
<body>
  <h2><?php echo ($escaped === '' ? 'Enter a search query' : "You searched for: $escaped"); ?></h2>
  
</body>
</html>