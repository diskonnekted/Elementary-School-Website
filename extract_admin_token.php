<?php
// Start session first 
session_start();

// Mock admin login
$_SESSION['admin_id'] = 1;

// Include required files
require_once 'admin/includes/functions.php';
require_once 'admin/models/News.php';
require_once 'admin/config/database.php';

echo "<h2>Extract Admin CSRF Token</h2>";

// Get database connection
$database = new Database();
$db = $database->getConnection();
$news = new News($db);

// Get the token that would be used in admin dashboard
$csrf_token = generateCSRFToken();

echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
echo "<p><strong>Admin ID:</strong> " . ($_SESSION['admin_id'] ?? 'NOT SET') . "</p>";
echo "<p><strong>CSRF Token:</strong> <br><code style='background: #f4f4f4; padding: 5px; font-size: 12px; word-break: break-all;'>" . $csrf_token . "</code></p>";

// Get current news list
$newsList = $news->getAll(10, 0);
if (!empty($newsList)) {
    echo "<h3>Available News for Delete Testing</h3>";
    foreach ($newsList as $item) {
        $delete_url = "admin/news.php?action=delete&id=" . $item['id'] . "&csrf_token=" . $csrf_token;
        echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ddd;'>";
        echo "<strong>ID " . $item['id'] . ":</strong> " . htmlspecialchars($item['title']) . "<br>";
        echo "<a href='" . $delete_url . "' style='color: red; text-decoration: none;' onclick=\"return confirm('Delete this news?')\">";
        echo "🗑️ Test Delete</a>";
        echo "</div>";
    }
}

// Validation test
echo "<h3>Token Validation Test</h3>";
$is_valid = validateCSRFToken($csrf_token);
echo "Token validation result: " . ($is_valid ? '✅ VALID' : '❌ INVALID') . "<br>";

echo "<h3>Direct Admin Access</h3>";
echo "<a href='admin/news.php' target='_blank'>🔗 Open Admin Dashboard</a>";
?>

<style>
body { 
    font-family: Arial, sans-serif; 
    margin: 20px; 
    line-height: 1.6; 
}
h2, h3 { 
    color: #333; 
    border-bottom: 1px solid #eee; 
    padding-bottom: 5px; 
}
code { 
    font-family: monospace; 
}
</style>
