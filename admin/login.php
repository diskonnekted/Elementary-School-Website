<?php
require_once 'config/database.php';
require_once 'models/User.php';
session_start();

// Functions
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Redirect jika sudah login
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    if (empty($username) || empty($password)) {
        $error_message = 'Username dan password harus diisi!';
    } else {
        $database = new Database();
        $db = $database->getConnection();
        $userModel = new User($db);
        
        $result = $userModel->authenticate($username, $password);
        
        if ($result['success']) {
            $user = $result['user'];
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_username'] = $user['username'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_email'] = $user['email'];
            
            // Set cookie jika remember me checked
            if ($remember) {
                setcookie('admin_remember', $user['id'], time() + (30 * 24 * 60 * 60), '/'); // 30 hari
            }
            
            header('Location: index.php');
            exit;
        } else {
            $error_message = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SD Cerdas Ceria</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-gradient-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gradient-custom min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <!-- Logo dan Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-4">
                <i class="fas fa-graduation-cap text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Admin Dashboard</h1>
            <p class="text-gray-600">SD Cerdas Ceria</p>
        </div>

        <!-- Alert Error -->
        <?php if ($error_message): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?= htmlspecialchars($error_message) ?>
        </div>
        <?php endif; ?>

        <!-- Form Login -->
        <form method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-2"></i>Username atau Email
                </label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Masukkan username atau email"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2"></i>Password
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10"
                        placeholder="Masukkan password"
                    >
                    <button 
                        type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        onclick="togglePassword()"
                    >
                        <i class="fas fa-eye text-gray-400" id="toggle-icon"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                </label>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Lupa password?</a>
            </div>

            <button 
                type="submit" 
                class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-2 px-4 rounded-lg hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200"
            >
                <i class="fas fa-sign-in-alt mr-2"></i>Masuk
            </button>
        </form>

        <!-- Info Default Login -->
        <div class="mt-8 p-4 bg-blue-50 rounded-lg">
            <h3 class="text-sm font-medium text-blue-800 mb-2">
                <i class="fas fa-info-circle mr-2"></i>Info Login Default:
            </h3>
            <div class="text-xs text-blue-700 space-y-2">
                <div class="border-b border-blue-200 pb-1">
                    <p><strong>Admin:</strong> admin / admin123</p>
                </div>
                <div class="border-b border-blue-200 pb-1">
                    <p><strong>Guru:</strong> guru1 / guru123</p>
                </div>
                <div>
                    <p><strong>Demo (Read-only):</strong> demo / demo123</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-xs text-gray-500">
            © 2024 SD Cerdas Ceria. All rights reserved.
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggle-icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto focus pada username field
        document.getElementById('username').focus();
    </script>
</body>
</html>
