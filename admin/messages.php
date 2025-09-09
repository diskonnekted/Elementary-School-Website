<?php
require_once 'includes/functions.php';
require_once 'config/database.php';
require_once 'models/ContactMessage.php';

// Check if user is logged in and is admin using function from functions.php
requireLogin();

// Initialize database and models
$database = new Database();
$db = $database->getConnection();
$contactMessage = new ContactMessage($db);

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'update_status':
            $result = $contactMessage->updateStatus(
                $_POST['id'], 
                $_POST['status'], 
                $_SESSION['admin_username'] ?? 'Admin',
                $_POST['admin_notes'] ?? null
            );
            echo json_encode($result);
            exit;
            
        case 'delete_message':
            $result = $contactMessage->delete($_POST['id']);
            echo json_encode($result);
            exit;
            
        case 'mark_as_read':
            $result = $contactMessage->markAsRead($_POST['id']);
            echo json_encode($result);
            exit;
    }
}

// Get filter parameters
$status_filter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Get messages and stats
$messages = $contactMessage->getAll($status_filter, $per_page, $offset, $search);
$stats = $contactMessage->getStats();

// Get total count for pagination
$total_query = "SELECT COUNT(*) as total FROM contact_messages";
$conditions = [];
$params = [];

if (!empty($status_filter)) {
    $conditions[] = "status = ?";
    $params[] = $status_filter;
}

if (!empty($search)) {
    $conditions[] = "(name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)";
    $searchParam = "%$search%";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
}

if (!empty($conditions)) {
    $total_query .= " WHERE " . implode(" AND ", $conditions);
}

$total_stmt = $db->prepare($total_query);
$total_stmt->execute($params);
$total_records = $total_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_records / $per_page);

$page_title = 'Messages';
include 'includes/header.php';
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Pesan Kontak</h1>
                    <p class="mt-2 text-sm text-gray-600">Kelola pesan dari form kontak website</p>
                </div>
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white text-center">
                        <div class="text-2xl font-bold"><?php echo $stats['total']; ?></div>
                        <div class="text-xs opacity-90">Total</div>
                    </div>
                    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl p-4 text-white text-center">
                        <div class="text-2xl font-bold"><?php echo $stats['unread']; ?></div>
                        <div class="text-xs opacity-90">Belum Dibaca</div>
                    </div>
                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-4 text-white text-center">
                        <div class="text-2xl font-bold"><?php echo $stats['read']; ?></div>
                        <div class="text-xs opacity-90">Sudah Dibaca</div>
                    </div>
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white text-center">
                        <div class="text-2xl font-bold"><?php echo $stats['replied']; ?></div>
                        <div class="text-xs opacity-90">Sudah Dibalas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-64">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pesan</label>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                           placeholder="Cari nama, email, subjek, atau isi pesan..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="unread" <?php echo $status_filter === 'unread' ? 'selected' : ''; ?>>Belum Dibaca</option>
                        <option value="read" <?php echo $status_filter === 'read' ? 'selected' : ''; ?>>Sudah Dibaca</option>
                        <option value="replied" <?php echo $status_filter === 'replied' ? 'selected' : ''; ?>>Sudah Dibalas</option>
                        <option value="archived" <?php echo $status_filter === 'archived' ? 'selected' : ''; ?>>Diarsipkan</option>
                    </select>
                </div>
                
                <div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
                
                <?php if (!empty($search) || !empty($status_filter)): ?>
                <div>
                    <a href="messages.php" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg border border-gray-300 transition-colors">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                </div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Messages List -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <?php if (empty($messages)): ?>
                <div class="text-center py-16">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak ada pesan</h3>
                    <p class="text-gray-500">
                        <?php if (!empty($search) || !empty($status_filter)): ?>
                            Tidak ada pesan yang sesuai dengan filter yang dipilih.
                        <?php else: ?>
                            Belum ada pesan masuk dari form kontak.
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengirim</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjek</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($messages as $message): ?>
                                <tr class="hover:bg-gray-50 <?php echo $message['status'] === 'unread' ? 'bg-blue-50' : ''; ?>">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10">
                                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                                    <?php echo strtoupper(substr($message['name'], 0, 2)); ?>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($message['name']); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($message['email']); ?></div>
                                                <?php if (!empty($message['phone'])): ?>
                                                    <div class="text-xs text-gray-400"><?php echo htmlspecialchars($message['phone']); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 font-medium"><?php echo htmlspecialchars($message['subject']); ?></div>
                                        <div class="text-sm text-gray-500 line-clamp-2">
                                            <?php echo htmlspecialchars(substr($message['message'], 0, 100)) . (strlen($message['message']) > 100 ? '...' : ''); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php echo $contactMessage->getStatusBadge($message['status']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div><?php echo date('d M Y', strtotime($message['created_at'])); ?></div>
                                        <div class="text-xs"><?php echo date('H:i', strtotime($message['created_at'])); ?></div>
                                        <div class="text-xs text-gray-400"><?php echo $contactMessage->formatTimeAgo($message['created_at']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium space-x-2">
                                        <button onclick="viewMessage(<?php echo $message['id']; ?>)" 
                                                class="text-blue-600 hover:text-blue-900 transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <div class="relative inline-block text-left">
                                            <button onclick="toggleDropdown(<?php echo $message['id']; ?>)" 
                                                    class="text-gray-600 hover:text-gray-900 transition-colors">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            
                                            <div id="dropdown-<?php echo $message['id']; ?>" 
                                                 class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                                <div class="py-1">
                                                    <?php if ($message['status'] === 'unread'): ?>
                                                        <a href="#" onclick="updateStatus(<?php echo $message['id']; ?>, 'read')" 
                                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i class="fas fa-eye mr-2"></i>Tandai Sudah Dibaca
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($message['status'] !== 'replied'): ?>
                                                        <a href="#" onclick="updateStatus(<?php echo $message['id']; ?>, 'replied')" 
                                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i class="fas fa-reply mr-2"></i>Tandai Sudah Dibalas
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <a href="#" onclick="updateStatus(<?php echo $message['id']; ?>, 'archived')" 
                                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fas fa-archive mr-2"></i>Arsipkan
                                                    </a>
                                                    
                                                    <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>?subject=Re: <?php echo htmlspecialchars($message['subject']); ?>" 
                                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fas fa-envelope mr-2"></i>Balas via Email
                                                    </a>
                                                    
                                                    <div class="border-t border-gray-100"></div>
                                                    <a href="#" onclick="deleteMessage(<?php echo $message['id']; ?>)" 
                                                       class="block px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                        <i class="fas fa-trash mr-2"></i>Hapus
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <?php echo (($page - 1) * $per_page) + 1; ?> - <?php echo min($page * $per_page, $total_records); ?> dari <?php echo $total_records; ?> pesan
                </div>
                
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>&status=<?php echo $status_filter; ?>&search=<?php echo urlencode($search); ?>" 
                           class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <a href="?page=<?php echo $i; ?>&status=<?php echo $status_filter; ?>&search=<?php echo urlencode($search); ?>" 
                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo $i === $page ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&status=<?php echo $status_filter; ?>&search=<?php echo urlencode($search); ?>" 
                           class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Message Detail Modal -->
<div id="messageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Detail Pesan</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="modalContent" class="space-y-4">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Toggle dropdown menu
function toggleDropdown(id) {
    const dropdown = document.getElementById('dropdown-' + id);
    const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
    
    // Close all other dropdowns
    allDropdowns.forEach(d => {
        if (d.id !== 'dropdown-' + id) {
            d.classList.add('hidden');
        }
    });
    
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick*="toggleDropdown"]') && !event.target.closest('[id^="dropdown-"]')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));
    }
});

// View message details
async function viewMessage(id) {
    try {
        const response = await fetch('messages.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=get_message&id=${id}`
        });
        
        // For now, just show a simple modal with message details
        // You can enhance this to load actual message data
        showModal('Detail Pesan', `
            <div class="bg-blue-50 p-4 rounded-lg mb-4">
                <p class="text-blue-800">Fitur detail pesan akan segera tersedia.</p>
            </div>
        `);
    } catch (error) {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat memuat detail pesan', 'error');
    }
}

// Update message status
async function updateStatus(id, status) {
    try {
        const response = await fetch('messages.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=update_status&id=${id}&status=${status}`
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showAlert(result.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat mengupdate status', 'error');
    }
}

// Delete message
async function deleteMessage(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus pesan ini?')) {
        return;
    }
    
    try {
        const response = await fetch('messages.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete_message&id=${id}`
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showAlert(result.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat menghapus pesan', 'error');
    }
}

// Show modal
function showModal(title, content) {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('messageModal').classList.remove('hidden');
}

// Close modal
function closeModal() {
    document.getElementById('messageModal').classList.add('hidden');
}

// Show alert
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-100 text-green-800 border border-green-300' : 
        'bg-red-100 text-red-800 border border-red-300'
    }`;
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// Auto refresh unread count every 30 seconds
setInterval(() => {
    if (!document.hidden) {
        fetch('messages.php?ajax=stats')
            .then(response => response.json())
            .then(data => {
                if (data.unread !== undefined) {
                    // Update unread count in navigation if needed
                }
            })
            .catch(console.error);
    }
}, 30000);
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?php include 'includes/footer.php'; ?>
