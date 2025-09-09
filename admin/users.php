<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
require_once 'models/User.php';

// Require admin or superadmin role
Auth::requireRole([Auth::ROLE_ADMIN, Auth::ROLE_SUPERADMIN]);

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);

// Get current user info
$current_user = Auth::getCurrentUser();

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Auth::blockWriteOperations(); // Block demo users
    
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create':
            $result = $userModel->create(
                $_POST['username'],
                $_POST['email'],
                $_POST['password'],
                $_POST['full_name'],
                $_POST['role'],
                $current_user['id']
            );
            Auth::setFlashMessage($result['success'] ? 'success' : 'error', $result['message']);
            break;
            
        case 'update':
            $result = $userModel->update(
                $_POST['user_id'],
                $_POST['username'],
                $_POST['email'],
                $_POST['full_name'],
                $_POST['role'],
                $_POST['status'],
                $current_user['id']
            );
            Auth::setFlashMessage($result['success'] ? 'success' : 'error', $result['message']);
            break;
            
        case 'update_password':
            $result = $userModel->updatePassword(
                $_POST['user_id'],
                $_POST['new_password'],
                $current_user['id']
            );
            Auth::setFlashMessage($result['success'] ? 'success' : 'error', $result['message']);
            break;
            
        case 'delete':
            $result = $userModel->delete($_POST['user_id']);
            Auth::setFlashMessage($result['success'] ? 'success' : 'error', $result['message']);
            break;
            
        case 'update_status':
            $result = $userModel->updateStatus(
                $_POST['user_id'],
                $_POST['status'],
                $current_user['id']
            );
            Auth::setFlashMessage($result['success'] ? 'success' : 'error', $result['message']);
            break;
    }
    
    // Redirect to prevent form resubmission
    header('Location: users.php');
    exit;
}

// Handle GET requests for AJAX
if (isset($_GET['action']) && $_GET['action'] === 'get_user' && isset($_GET['id'])) {
    $user = $userModel->getById($_GET['id']);
    header('Content-Type: application/json');
    echo json_encode($user);
    exit;
}

// Get filters
$role_filter = $_GET['role'] ?? '';
$status_filter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

// Get users
$users = $userModel->getAll($role_filter, $status_filter, $limit, $offset, $search);

// Get user statistics
$stats = $userModel->getStats();

// Set page title for admin header
$page_title = 'User Management';
?>
<?php include 'includes/admin_header.php'; ?>

    <!-- Header and Stats -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">User Management</h2>
            <?php if (!Auth::isReadOnly()): ?>
            <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus mr-2"></i>Tambah User
            </button>
            <?php endif; ?>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?></p>
                    </div>
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Admin</p>
                        <p class="text-2xl font-bold text-blue-600"><?= $stats['admin'] + $stats['superadmin'] ?></p>
                    </div>
                    <i class="fas fa-user-shield text-blue-600 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Guru</p>
                        <p class="text-2xl font-bold text-green-600"><?= $stats['guru'] ?></p>
                    </div>
                    <i class="fas fa-chalkboard-teacher text-green-600 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Demo</p>
                        <p class="text-2xl font-bold text-gray-600"><?= $stats['demo'] ?></p>
                    </div>
                    <i class="fas fa-eye text-gray-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" name="search" placeholder="Cari username, email, atau nama..." 
                       value="<?= htmlspecialchars($search) ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select name="role" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Role</option>
                    <option value="superadmin" <?= $role_filter === 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
                    <option value="admin" <?= $role_filter === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="guru" <?= $role_filter === 'guru' ? 'selected' : '' ?>>Guru</option>
                    <option value="demo" <?= $role_filter === 'demo' ? 'selected' : '' ?>>Demo</option>
                </select>
            </div>
            <div>
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="active" <?= $status_filter === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $status_filter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    <option value="suspended" <?= $status_filter === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            <a href="users.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-refresh mr-2"></i>Reset
            </a>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <?php if (!Auth::isReadOnly()): ?>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($users as $user): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($user['full_name']) ?></div>
                            <div class="text-sm text-gray-500">@<?= htmlspecialchars($user['username']) ?></div>
                            <div class="text-xs text-gray-400"><?= htmlspecialchars($user['email']) ?></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?= Auth::getRoleBadge($user['role']) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?= $userModel->getStatusBadge($user['status']) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Never' ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                    </td>
                    <?php if (!Auth::isReadOnly()): ?>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="openEditModal(<?= $user['id'] ?>)" class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="openPasswordModal(<?= $user['id'] ?>)" class="text-yellow-600 hover:text-yellow-900">
                                <i class="fas fa-key"></i>
                            </button>
                            <?php if ($user['id'] != $current_user['id']): ?>
                            <button onclick="toggleStatus(<?= $user['id'] ?>, '<?= $user['status'] ?>')" class="text-green-600 hover:text-green-900">
                                <i class="fas <?= $user['status'] === 'active' ? 'fa-ban' : 'fa-check' ?>"></i>
                            </button>
                            <button onclick="confirmDelete(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>')" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (empty($users)): ?>
    <div class="text-center py-8">
        <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
        <p class="text-gray-500">Tidak ada user ditemukan</p>
    </div>
    <?php endif; ?>

<!-- Modals akan ditambahkan dengan JavaScript -->
<?php include 'includes/user_modals.php'; ?>

<script>
        // Auto hide flash messages
        setTimeout(() => {
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                flashMessage.style.display = 'none';
            }
        }, 5000);

        // Modal functions
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openEditModal(userId) {
            fetch(`users.php?action=get_user&id=${userId}`)
                .then(response => response.json())
                .then(user => {
                    document.getElementById('edit_user_id').value = user.id;
                    document.getElementById('edit_username').value = user.username;
                    document.getElementById('edit_email').value = user.email;
                    document.getElementById('edit_full_name').value = user.full_name;
                    document.getElementById('edit_role').value = user.role;
                    document.getElementById('edit_status').value = user.status;
                    document.getElementById('editModal').classList.remove('hidden');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openPasswordModal(userId) {
            document.getElementById('password_user_id').value = userId;
            document.getElementById('passwordModal').classList.remove('hidden');
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
        }

        function confirmDelete(userId, username) {
            if (confirm(`Yakin ingin menghapus user "${username}"?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="user_id" value="${userId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function toggleStatus(userId, currentStatus) {
            const newStatus = currentStatus === 'active' ? 'suspended' : 'active';
            const action = newStatus === 'active' ? 'mengaktifkan' : 'menangguhkan';
            
            if (confirm(`Yakin ingin ${action} user ini?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="user_id" value="${userId}">
                    <input type="hidden" name="status" value="${newStatus}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
</script>

<?php include 'includes/admin_footer.php'; ?>
