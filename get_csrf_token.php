<?php
session_start();

// Include functions
require_once 'admin/includes/functions.php';

// Generate atau get CSRF token
$csrf_token = generateCSRFToken();

echo "<h2>CSRF Token untuk Testing Delete</h2>";
echo "<p><strong>CSRF Token:</strong> <code style='background: #f4f4f4; padding: 5px; border-radius: 3px;'>" . $csrf_token . "</code></p>";

echo "<h3>Test Delete Links</h3>";
echo "<p>Gunakan link ini untuk test delete (ganti ID sesuai kebutuhan):</p>";

// Test dengan beberapa ID
$test_ids = [1, 2, 3, 4, 5];
foreach ($test_ids as $test_id) {
    $delete_url = "admin/news.php?action=delete&id=" . $test_id . "&csrf_token=" . $csrf_token;
    echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 5px;'>";
    echo "<strong>Delete News ID " . $test_id . ":</strong><br>";
    echo "<a href='" . $delete_url . "' style='color: red;' onclick=\"return confirm('Yakin ingin menghapus berita ID " . $test_id . "?')\">🗑️ Delete News " . $test_id . "</a><br>";
    echo "<small>URL: <code>" . $delete_url . "</code></small>";
    echo "</div>";
}

echo "<h3>Token Validation Test</h3>";
echo "Current token validation: " . (validateCSRFToken($csrf_token) ? '✅ Valid' : '❌ Invalid') . "<br>";
echo "Session token: " . ($_SESSION['csrf_token'] ?? 'NOT SET') . "<br>";

echo "<h3>Admin Dashboard</h3>";
echo "<a href='admin/news.php' target='_blank'>🔗 Open Admin News Dashboard</a>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    line-height: 1.6;
}
h2, h3 {
    color: #333;
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
}
code {
    font-family: 'Courier New', monospace;
    font-size: 12px;
}
a {
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}
</style>
