<?php
// Authentication middleware untuk role-based access control
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Auth {
    // User roles
    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_ADMIN = 'admin';
    const ROLE_GURU = 'guru';
    const ROLE_DEMO = 'demo';

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public static function getCurrentUser() {
        if (!self::isLoggedIn()) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['user_username'] ?? null,
            'name' => $_SESSION['user_name'] ?? null,
            'role' => $_SESSION['user_role'] ?? null,
            'email' => $_SESSION['user_email'] ?? null
        ];
    }

    public static function getUserRole() {
        return $_SESSION['user_role'] ?? null;
    }

    public static function requireLogin($redirect_url = '/admin/login.php') {
        if (!self::isLoggedIn()) {
            header('Location: ' . $redirect_url);
            exit;
        }
    }

    public static function requireRole($required_roles, $redirect_url = '/admin/index.php') {
        self::requireLogin();
        
        $current_role = self::getUserRole();
        
        // Convert single role to array
        if (!is_array($required_roles)) {
            $required_roles = [$required_roles];
        }
        
        if (!in_array($current_role, $required_roles)) {
            // Log unauthorized access attempt
            error_log("Unauthorized access attempt by user " . $_SESSION['user_username'] . " (role: $current_role) to " . $_SERVER['REQUEST_URI']);
            
            // Set flash message
            $_SESSION['flash_error'] = 'Anda tidak memiliki akses ke halaman tersebut.';
            header('Location: ' . $redirect_url);
            exit;
        }
    }

    public static function canManageUsers() {
        $role = self::getUserRole();
        return in_array($role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN]);
    }

    public static function canEditContent() {
        $role = self::getUserRole();
        return in_array($role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN, self::ROLE_GURU]);
    }

    public static function isReadOnly() {
        $role = self::getUserRole();
        return $role === self::ROLE_DEMO;
    }

    public static function canDeleteContent() {
        $role = self::getUserRole();
        return in_array($role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN]);
    }

    public static function canViewSettings() {
        $role = self::getUserRole();
        return in_array($role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN]);
    }

    public static function canManageMessages() {
        $role = self::getUserRole();
        return in_array($role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN, self::ROLE_GURU]);
    }

    public static function logout() {
        // Clear all session data
        $_SESSION = array();
        
        // Delete session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Clear remember cookie
        if (isset($_COOKIE['admin_remember'])) {
            setcookie('admin_remember', '', time() - 3600, '/');
        }
        
        // Destroy session
        session_destroy();
    }

    public static function getRoleBadge($role) {
        $badges = [
            'superadmin' => '<span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Super Admin</span>',
            'admin' => '<span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Admin</span>',
            'guru' => '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Guru</span>',
            'demo' => '<span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Demo</span>'
        ];

        return $badges[$role] ?? '<span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Unknown</span>';
    }

    public static function getFlashMessage($type = '') {
        $message = null;
        
        if ($type) {
            $key = 'flash_' . $type;
            if (isset($_SESSION[$key])) {
                $message = $_SESSION[$key];
                unset($_SESSION[$key]);
            }
        } else {
            // Get any flash message
            $flash_keys = ['flash_success', 'flash_error', 'flash_warning', 'flash_info'];
            foreach ($flash_keys as $key) {
                if (isset($_SESSION[$key])) {
                    $message = ['type' => str_replace('flash_', '', $key), 'message' => $_SESSION[$key]];
                    unset($_SESSION[$key]);
                    break;
                }
            }
        }
        
        return $message;
    }

    public static function setFlashMessage($type, $message) {
        $_SESSION['flash_' . $type] = $message;
    }

    // Check if current request is for a read operation
    public static function isReadOperation() {
        $method = $_SERVER['REQUEST_METHOD'];
        $action = $_GET['action'] ?? '';
        
        // GET requests are generally read operations
        if ($method === 'GET') {
            return true;
        }
        
        // Specific read actions
        $read_actions = ['view', 'search', 'filter', 'export'];
        if (in_array($action, $read_actions)) {
            return true;
        }
        
        return false;
    }

    // Block write operations for demo users
    public static function blockWriteOperations() {
        if (self::isReadOnly() && !self::isReadOperation()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Demo user tidak dapat melakukan perubahan data.'
            ]);
            exit;
        }
    }
}

// Helper function to sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Helper function to check if user has permission for specific action
function hasPermission($action) {
    $role = Auth::getUserRole();
    
    switch ($action) {
        case 'manage_users':
            return Auth::canManageUsers();
        case 'edit_content':
            return Auth::canEditContent();
        case 'delete_content':
            return Auth::canDeleteContent();
        case 'view_settings':
            return Auth::canViewSettings();
        case 'manage_messages':
            return Auth::canManageMessages();
        default:
            return false;
    }
}

// Helper function to format time ago
function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'baru saja';
    if ($time < 3600) return floor($time/60) . ' menit yang lalu';
    if ($time < 86400) return floor($time/3600) . ' jam yang lalu';
    if ($time < 2592000) return floor($time/86400) . ' hari yang lalu';
    if ($time < 31536000) return floor($time/2592000) . ' bulan yang lalu';
    return floor($time/31536000) . ' tahun yang lalu';
}
?>
