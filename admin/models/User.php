<?php
class User {
    private $conn;
    private $table_name = "users";

    // User roles
    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_ADMIN = 'admin';
    const ROLE_GURU = 'guru';
    const ROLE_DEMO = 'demo';

    // User status
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';

    public function __construct($db) {
        $this->conn = $db;
        $this->createTable();
    }

    private function createTable() {
        $query = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(150) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            role ENUM('superadmin', 'admin', 'guru', 'demo') DEFAULT 'demo',
            status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
            last_login TIMESTAMP NULL,
            login_attempts INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by INT NULL,
            updated_by INT NULL,
            INDEX idx_username (username),
            INDEX idx_email (email),
            INDEX idx_role (role),
            INDEX idx_status (status),
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
            FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        try {
            $this->conn->exec($query);
        } catch(PDOException $e) {
            error_log("Error creating users table: " . $e->getMessage());
        }
    }

    public function create($username, $email, $password, $full_name, $role = self::ROLE_DEMO, $created_by = null) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (username, email, password, full_name, role, created_by) 
                  VALUES (?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->conn->prepare($query);
            
            // Sanitize input
            $username = htmlspecialchars(strip_tags(trim($username)));
            $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
            $full_name = htmlspecialchars(strip_tags(trim($full_name)));
            
            // Validate required fields
            if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
                throw new Exception("Required fields are missing");
            }
            
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format");
            }
            
            // Validate username (alphanumeric and underscore only)
            if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
                throw new Exception("Username must be 3-50 characters (letters, numbers, underscore only)");
            }
            
            // Validate role
            $valid_roles = [self::ROLE_SUPERADMIN, self::ROLE_ADMIN, self::ROLE_GURU, self::ROLE_DEMO];
            if (!in_array($role, $valid_roles)) {
                throw new Exception("Invalid role");
            }
            
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt->execute([
                $username,
                $email,
                $hashed_password,
                $full_name,
                $role,
                $created_by
            ]);

            return [
                'success' => true,
                'id' => $this->conn->lastInsertId(),
                'message' => 'User created successfully'
            ];

        } catch(Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function authenticate($username, $password) {
        $query = "SELECT id, username, email, password, full_name, role, status, login_attempts 
                  FROM " . $this->table_name . " 
                  WHERE (username = ? OR email = ?) AND status = ?";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$username, $username, self::STATUS_ACTIVE]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Check if account is locked due to too many failed attempts
                if ($user['login_attempts'] >= 5) {
                    return [
                        'success' => false,
                        'message' => 'Account is locked due to too many failed login attempts. Please contact administrator.'
                    ];
                }

                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Reset login attempts and update last login
                    $this->updateLastLogin($user['id']);
                    $this->resetLoginAttempts($user['id']);
                    
                    unset($user['password']); // Remove password from return data
                    return [
                        'success' => true,
                        'user' => $user,
                        'message' => 'Login successful'
                    ];
                } else {
                    // Increment login attempts
                    $this->incrementLoginAttempts($user['id']);
                    return [
                        'success' => false,
                        'message' => 'Invalid credentials'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Invalid credentials'
                ];
            }

        } catch(PDOException $e) {
            error_log("Error authenticating user: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Database error'
            ];
        }
    }

    public function getAll($role = '', $status = '', $limit = 50, $offset = 0, $search = '') {
        $query = "SELECT id, username, email, full_name, role, status, last_login, created_at FROM " . $this->table_name;
        $conditions = [];
        $params = [];

        if (!empty($role)) {
            $conditions[] = "role = ?";
            $params[] = $role;
        }

        if (!empty($status)) {
            $conditions[] = "status = ?";
            $params[] = $status;
        }

        if (!empty($search)) {
            $conditions[] = "(username LIKE ? OR email LIKE ? OR full_name LIKE ?)";
            $searchParam = "%$search%";
            $params = array_merge($params, [$searchParam, $searchParam, $searchParam]);
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $limit = (int)$limit;
        $offset = (int)$offset;
        $query .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting users: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id) {
        $query = "SELECT id, username, email, full_name, role, status, last_login, created_at, updated_at 
                  FROM " . $this->table_name . " WHERE id = ?";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting user by ID: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $username, $email, $full_name, $role, $status, $updated_by = null) {
        $query = "UPDATE " . $this->table_name . " 
                  SET username = ?, email = ?, full_name = ?, role = ?, status = ?, updated_by = ?
                  WHERE id = ?";

        try {
            $stmt = $this->conn->prepare($query);
            
            // Sanitize input
            $username = htmlspecialchars(strip_tags(trim($username)));
            $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
            $full_name = htmlspecialchars(strip_tags(trim($full_name)));
            
            // Validate required fields
            if (empty($username) || empty($email) || empty($full_name)) {
                throw new Exception("Required fields are missing");
            }
            
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format");
            }
            
            // Validate username
            if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
                throw new Exception("Username must be 3-50 characters (letters, numbers, underscore only)");
            }
            
            // Validate role and status
            $valid_roles = [self::ROLE_SUPERADMIN, self::ROLE_ADMIN, self::ROLE_GURU, self::ROLE_DEMO];
            $valid_statuses = [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_SUSPENDED];
            
            if (!in_array($role, $valid_roles)) {
                throw new Exception("Invalid role");
            }
            
            if (!in_array($status, $valid_statuses)) {
                throw new Exception("Invalid status");
            }
            
            $stmt->execute([
                $username,
                $email,
                $full_name,
                $role,
                $status,
                $updated_by,
                $id
            ]);

            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'User updated successfully'];
            } else {
                return ['success' => false, 'message' => 'User not found or no changes made'];
            }

        } catch(Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updatePassword($id, $new_password, $updated_by = null) {
        $query = "UPDATE " . $this->table_name . " 
                  SET password = ?, updated_by = ?
                  WHERE id = ?";

        try {
            $stmt = $this->conn->prepare($query);
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $stmt->execute([
                $hashed_password,
                $updated_by,
                $id
            ]);

            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Password updated successfully'];
            } else {
                return ['success' => false, 'message' => 'User not found'];
            }

        } catch(PDOException $e) {
            error_log("Error updating password: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error'];
        }
    }

    public function delete($id) {
        // Check if user has dependencies (created_by or updated_by references)
        $check_query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE created_by = ? OR updated_by = ?";
        
        try {
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([$id, $id]);
            $dependencies = $check_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($dependencies['count'] > 0) {
                // Soft delete by setting status to inactive
                return $this->updateStatus($id, self::STATUS_INACTIVE);
            }
            
            // Hard delete if no dependencies
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'User deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'User not found'];
            }
        } catch(PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error'];
        }
    }

    public function updateStatus($id, $status, $updated_by = null) {
        $valid_statuses = [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_SUSPENDED];
        
        if (!in_array($status, $valid_statuses)) {
            return ['success' => false, 'message' => 'Invalid status'];
        }

        $query = "UPDATE " . $this->table_name . " SET status = ?, updated_by = ? WHERE id = ?";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$status, $updated_by, $id]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Status updated successfully'];
            } else {
                return ['success' => false, 'message' => 'User not found'];
            }
        } catch(PDOException $e) {
            error_log("Error updating user status: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error'];
        }
    }

    private function updateLastLogin($id) {
        $query = "UPDATE " . $this->table_name . " SET last_login = CURRENT_TIMESTAMP WHERE id = ?";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
        } catch(PDOException $e) {
            error_log("Error updating last login: " . $e->getMessage());
        }
    }

    private function incrementLoginAttempts($id) {
        $query = "UPDATE " . $this->table_name . " SET login_attempts = login_attempts + 1 WHERE id = ?";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
        } catch(PDOException $e) {
            error_log("Error incrementing login attempts: " . $e->getMessage());
        }
    }

    private function resetLoginAttempts($id) {
        $query = "UPDATE " . $this->table_name . " SET login_attempts = 0 WHERE id = ?";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
        } catch(PDOException $e) {
            error_log("Error resetting login attempts: " . $e->getMessage());
        }
    }

    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN role = 'superadmin' THEN 1 ELSE 0 END) as superadmin,
                    SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admin,
                    SUM(CASE WHEN role = 'guru' THEN 1 ELSE 0 END) as guru,
                    SUM(CASE WHEN role = 'demo' THEN 1 ELSE 0 END) as demo,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive,
                    SUM(CASE WHEN status = 'suspended' THEN 1 ELSE 0 END) as suspended
                  FROM " . $this->table_name;

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting user stats: " . $e->getMessage());
            return [
                'total' => 0,
                'superadmin' => 0,
                'admin' => 0, 
                'guru' => 0,
                'demo' => 0,
                'active' => 0,
                'inactive' => 0,
                'suspended' => 0
            ];
        }
    }

    public function getRoleBadge($role) {
        $badges = [
            'superadmin' => '<span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Super Admin</span>',
            'admin' => '<span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Admin</span>',
            'guru' => '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Guru</span>',
            'demo' => '<span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Demo</span>'
        ];

        return $badges[$role] ?? '<span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Unknown</span>';
    }

    public function getStatusBadge($status) {
        $badges = [
            'active' => '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Active</span>',
            'inactive' => '<span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Inactive</span>',
            'suspended' => '<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Suspended</span>'
        ];

        return $badges[$status] ?? '<span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Unknown</span>';
    }

    // Permission checking methods
    public function canManageUsers($role) {
        return in_array($role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN]);
    }

    public function canEditContent($role) {
        return in_array($role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN, self::ROLE_GURU]);
    }

    public function isReadOnly($role) {
        return $role === self::ROLE_DEMO;
    }
}
?>
