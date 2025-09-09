<?php
require_once 'admin/includes/functions.php';

echo "<h2>Quick Login Test</h2>";

// Test login dengan default credentials
$username = 'admin';
$password = 'admin123';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT id, username, password, full_name, role FROM admin_users WHERE (username = ? OR email = ?) AND is_active = 1";
$stmt = $db->prepare($query);
$stmt->execute([$username, $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && verifyPassword($password, $user['password'])) {
    // Login berhasil
    $_SESSION['admin_id'] = $user['id'];
    $_SESSION['admin_username'] = $user['username'];
    $_SESSION['admin_name'] = $user['full_name'];
    $_SESSION['admin_role'] = $user['role'];
    
    echo "<p style='color: green;'>✓ Login successful!</p>";
    echo "<p>User ID: " . $user['id'] . "</p>";
    echo "<p>Username: " . $user['username'] . "</p>";
    echo "<p>Name: " . $user['full_name'] . "</p>";
    echo "<p>Role: " . $user['role'] . "</p>";
    
    echo "<br><a href='admin/academic.php' style='background: #3B82F6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Academic Page</a>";
    echo "<br><br><a href='admin/index.php' style='background: #10B981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Dashboard</a>";
} else {
    echo "<p style='color: red;'>✗ Login failed!</p>";
    echo "<p>User found: " . ($user ? 'Yes' : 'No') . "</p>";
    if ($user) {
        echo "<p>Password verify: " . (verifyPassword($password, $user['password']) ? 'Yes' : 'No') . "</p>";
    }
}

echo "<br><br><h3>Session Status:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
a { margin-right: 10px; margin-bottom: 10px; display: inline-block; }
</style>
