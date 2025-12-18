<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    require_once __DIR__ . '/../admin/config/database.php';
    require_once __DIR__ . '/../admin/models/User.php';

    $database = new Database();
    $db = $database->getConnection();
    if (!$db) {
        throw new Exception('Database connection failed');
    }

    $userModel = new User($db);

    $role = isset($_GET['role']) ? strtolower(trim($_GET['role'])) : 'guru';
    $status = isset($_GET['status']) ? strtolower(trim($_GET['status'])) : 'active';
    $limit = isset($_GET['limit']) ? max(1, min(200, (int)$_GET['limit'])) : 200;
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($page - 1) * $limit;

    // Debug: table existence and counts
    $tableExists = false;
    $totalUsers = 0;
    try {
        $check = $db->query("SHOW TABLES LIKE 'admin_users'");
        $tableExists = $check && $check->rowCount() > 0;
        if ($tableExists) {
            $cnt = $db->query("SELECT COUNT(*) AS c FROM admin_users");
            $row = $cnt->fetch(PDO::FETCH_ASSOC);
            $totalUsers = (int)($row['c'] ?? 0);
        }
    } catch(Exception $e) {}

    $rows = $userModel->getAll($role, $status, $limit, $offset, '');
    if (empty($rows)) {
        $rows = $userModel->getAll($role, '', $limit, $offset, '');
    }

    $data = array_map(function($u) {
        return [
            'id' => (int)($u['id'] ?? 0),
            'username' => $u['username'] ?? '',
            'email' => $u['email'] ?? '',
            'full_name' => $u['full_name'] ?? '',
            'role' => $u['role'] ?? '',
            'subject' => $u['subject'] ?? null,
            'bio' => $u['bio'] ?? null,
            'photo' => isset($u['photo_filename']) && $u['photo_filename']
                ? '/admin/uploads/teachers/' . $u['photo_filename']
                : null
        ];
    }, $rows ?: []);

    // extra debug: list raw roles present
    $rawRoles = [];
    try {
        $all = $db->query("SELECT id, full_name, role, is_active FROM admin_users");
        while ($r = $all->fetch(PDO::FETCH_ASSOC)) {
            $rawRoles[] = [
                'id' => (int)($r['id'] ?? 0),
                'full_name' => $r['full_name'] ?? '',
                'role' => $r['role'] ?? '',
                'is_active' => $r['is_active'] ?? null
            ];
        }
    } catch(Exception $e) {}

    echo json_encode([
        'success' => true,
        'count' => count($data),
        'data' => $data
        , 'debug' => [
            'admin_users_exists' => $tableExists,
            'admin_users_total' => $totalUsers,
            'roles' => $rawRoles
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch(Exception $e) {
    http_response_code($e->getCode() ?: 400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
