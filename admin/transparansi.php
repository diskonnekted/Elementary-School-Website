<?php
require_once 'includes/functions.php';
require_once 'config/database.php';
require_once 'models/Transparency.php';

// Check login - use the function from functions.php
requireLogin();

$database = new Database();
$db = $database->getConnection();
$transparency = new Transparency($db);

// Get current user for admin context
$current_user = getCurrentUser();

$message = '';
$messageType = '';

// Handle form submissions
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                // Validate data
                $errors = $transparency->validate($_POST);
                if (empty($errors)) {
                    // Handle file upload
                    $fileName = null;
                    if (isset($_FILES['file_attachment']) && $_FILES['file_attachment']['error'] == 0) {
                        $fileName = $transparency->uploadFile($_FILES['file_attachment']);
                    }
                    
                    // Set properties
                    $transparency->title = $_POST['title'];
                    $transparency->content = $_POST['content'];
                    $transparency->section_type = $_POST['section_type'];
                    $transparency->file_attachment = $fileName;
                    $transparency->is_active = isset($_POST['is_active']) ? $_POST['is_active'] : 1;
                    $transparency->sort_order = isset($_POST['sort_order']) ? $_POST['sort_order'] : 1;
                    
                    if ($transparency->create()) {
                        $message = 'Data transparansi berhasil ditambahkan!';
                        $messageType = 'success';
                    } else {
                        $message = 'Error: Gagal menambahkan data';
                        $messageType = 'error';
                    }
                } else {
                    $message = 'Error: ' . implode(', ', $errors);
                    $messageType = 'error';
                }
                break;
                
            case 'update':
                // Validate data
                $errors = $transparency->validate($_POST);
                if (empty($errors)) {
                    // Get current data
                    $currentData = $transparency->getById($_POST['id']);
                    
                    // Handle file upload
                    $fileName = $currentData['file_attachment']; // Keep current file by default
                    if (isset($_FILES['file_attachment']) && $_FILES['file_attachment']['error'] == 0) {
                        $fileName = $transparency->uploadFile($_FILES['file_attachment']);
                        // Delete old file if exists and new upload successful
                        if ($fileName && $currentData['file_attachment']) {
                            $oldFilePath = 'uploads/attachments/' . $currentData['file_attachment'];
                            if (file_exists($oldFilePath)) {
                                unlink($oldFilePath);
                            }
                        }
                    }
                    
                    // Set properties
                    $transparency->id = $_POST['id'];
                    $transparency->title = $_POST['title'];
                    $transparency->content = $_POST['content'];
                    $transparency->section_type = $_POST['section_type'];
                    $transparency->file_attachment = $fileName;
                    $transparency->is_active = isset($_POST['is_active']) ? $_POST['is_active'] : 1;
                    $transparency->sort_order = isset($_POST['sort_order']) ? $_POST['sort_order'] : 1;
                    
                    if ($transparency->update()) {
                        $message = 'Data transparansi berhasil diperbarui!';
                        $messageType = 'success';
                    } else {
                        $message = 'Error: Gagal memperbarui data';
                        $messageType = 'error';
                    }
                } else {
                    $message = 'Error: ' . implode(', ', $errors);
                    $messageType = 'error';
                }
                break;
        }
    }
}

// Handle GET requests (delete, toggle)
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'delete':
            if (isset($_GET['id'])) {
                $data = $transparency->getById($_GET['id']);
                if ($data) {
                    $transparency->id = $_GET['id'];
                    $transparency->file_attachment = $data['file_attachment'];
                    
                    if ($transparency->delete()) {
                        $message = 'Data transparansi berhasil dihapus!';
                        $messageType = 'success';
                    } else {
                        $message = 'Error: Gagal menghapus data';
                        $messageType = 'error';
                    }
                }
            }
            break;
            
        case 'toggle':
            if (isset($_GET['id'])) {
                if ($transparency->toggleActive($_GET['id'])) {
                    $message = 'Status berhasil diubah!';
                    $messageType = 'success';
                } else {
                    $message = 'Error: Gagal mengubah status';
                    $messageType = 'error';
                }
            }
            break;
    }
}

// Get data for editing
$editData = null;
if (isset($_GET['edit']) && $_GET['edit']) {
    $editData = $transparency->getById($_GET['edit']);
}

// Get all transparency data (including inactive for admin)
$query = "SELECT * FROM transparency ORDER BY section_type, sort_order ASC, created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$transparencies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$stats = $transparency->getStats();
?>
<?php 
$page_title = 'Kelola Transparansi';
include 'includes/header.php'; 
?>

<!-- Transparansi Management Content -->
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Kelola Transparansi</h1>
                        <p class="text-gray-600 mt-2">Kelola informasi transparansi dan akuntabilitas sekolah</p>
                    </div>
                    <button onclick="toggleModal('addModal')" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Data Transparansi
                    </button>
                </div>

                <!-- Message -->
                <?php if ($message): ?>
                <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'; ?>">
                    <p class="<?php echo $messageType === 'success' ? 'text-green-700' : 'text-red-700'; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </p>
                </div>
                <?php endif; ?>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <?php
                    $categoryNames = [
                        'financial' => ['name' => 'Laporan Keuangan', 'icon' => '💰', 'color' => 'blue'],
                        'budget' => ['name' => 'Anggaran Sekolah', 'icon' => '📊', 'color' => 'green'],
                        'governance' => ['name' => 'Tata Kelola', 'icon' => '🏛️', 'color' => 'purple'],
                        'reports' => ['name' => 'Laporan Berkala', 'icon' => '📋', 'color' => 'yellow'],
                        'policies' => ['name' => 'Kebijakan', 'icon' => '📜', 'color' => 'indigo'],
                        'procurement' => ['name' => 'Pengadaan', 'icon' => '🛒', 'color' => 'pink'],
                        'other' => ['name' => 'Lainnya', 'icon' => '📄', 'color' => 'gray']
                    ];
                    
                    $totalItems = $stats['total'] ?? 0;
                    ?>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Data</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $totalItems; ?></p>
                            </div>
                            <div class="text-3xl">📊</div>
                        </div>
                    </div>
                    
                    <?php if (isset($stats['by_section']) && is_array($stats['by_section'])): ?>
                        <?php $sectionCount = 0; ?>
                        <?php foreach ($stats['by_section'] as $section => $count): ?>
                            <?php if ($sectionCount >= 3) break; ?>
                            <?php $category = $categoryNames[$section] ?? ['name' => $section, 'icon' => '📄', 'color' => 'gray']; ?>
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600"><?php echo $category['name']; ?></p>
                                        <p class="text-2xl font-bold text-gray-900"><?php echo $count; ?></p>
                                    </div>
                                    <div class="text-3xl"><?php echo $category['icon']; ?></div>
                                </div>
                            </div>
                            <?php $sectionCount++; ?>
                        <?php endforeach; ?>
                        
                        <?php while ($sectionCount < 3): ?>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Aktif</p>
                                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['active'] ?? 0; ?></p>
                                </div>
                                <div class="text-3xl">✅</div>
                            </div>
                        </div>
                        <?php $sectionCount++; break; ?>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>

                <!-- Data Table -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Data Transparansi</h2>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (empty($transparencies)): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada data transparansi
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($transparencies as $item): ?>
                                    <?php 
                                    $category = $categoryNames[$item['section_type']] ?? ['name' => $item['section_type'], 'icon' => '📄', 'color' => 'gray'];
                                    ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-lg mr-2"><?php echo $category['icon']; ?></span>
                                                <span class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars(substr($item['title'], 0, 50)); ?>
                                                <?php if (strlen($item['title']) > 50) echo '...'; ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?php echo htmlspecialchars(substr(strip_tags($item['content']), 0, 80)); ?>
                                                <?php if (strlen(strip_tags($item['content'])) > 80) echo '...'; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php if ($item['file_attachment']): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    📎 Ada File
                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $item['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                <?php echo $item['is_active'] ? '✅ Aktif' : '❌ Nonaktif'; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <?php echo $item['sort_order']; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('d/m/Y', strtotime($item['created_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <button onclick="editItem(<?php echo htmlspecialchars(json_encode($item)); ?>)"
                                                    class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                            <a href="?action=toggle&id=<?php echo $item['id']; ?>" 
                                               class="text-yellow-600 hover:text-yellow-900">
                                                <?php echo $item['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $item['id']; ?>" 
                                               onclick="return confirm('Yakin ingin menghapus data ini?')"
                                               class="text-red-600 hover:text-red-900">Hapus</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
</div>

<?php include 'includes/footer.php'; ?>

    <!-- Add Modal -->
    <div id="addModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50"></div>
            <div class="relative bg-white rounded-lg max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Tambah Data Transparansi</h3>
                </div>
                
                <form method="POST" enctype="multipart/form-data" class="p-6">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul *</label>
                            <input type="text" name="title" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                            <select name="section_type" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Kategori</option>
                                <option value="financial">💰 Laporan Keuangan</option>
                                <option value="budget">📊 Anggaran Sekolah</option>
                                <option value="governance">🏛️ Tata Kelola</option>
                                <option value="reports">📋 Laporan Berkala</option>
                                <option value="policies">📜 Kebijakan</option>
                                <option value="procurement">🛒 Pengadaan</option>
                                <option value="other">📄 Lainnya</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Konten *</label>
                            <textarea name="content" rows="6" required 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Deskripsi atau konten transparansi..."></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload File</label>
                            <input type="file" name="file_attachment" 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Format: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG (Maks. 5MB)</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                                <input type="number" name="sort_order" value="1" min="1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="is_active" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="toggleModal('addModal')" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50"></div>
            <div class="relative bg-white rounded-lg max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Edit Data Transparansi</h3>
                </div>
                
                <form method="POST" enctype="multipart/form-data" class="p-6">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" id="edit_id">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul *</label>
                            <input type="text" name="title" id="edit_title" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                            <select name="section_type" id="edit_section_type" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Kategori</option>
                                <option value="financial">💰 Laporan Keuangan</option>
                                <option value="budget">📊 Anggaran Sekolah</option>
                                <option value="governance">🏛️ Tata Kelola</option>
                                <option value="reports">📋 Laporan Berkala</option>
                                <option value="policies">📜 Kebijakan</option>
                                <option value="procurement">🛒 Pengadaan</option>
                                <option value="other">📄 Lainnya</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Konten *</label>
                            <textarea name="content" id="edit_content" rows="6" required 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload File Baru (opsional)</label>
                            <input type="file" name="file_attachment" 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah file</p>
                            <div id="current_file" class="text-sm text-gray-600 mt-1"></div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                                <input type="number" name="sort_order" id="edit_sort_order" min="1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="is_active" id="edit_is_active"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="toggleModal('editModal')" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.toggle('hidden');
    }
    
    function editItem(item) {
        document.getElementById('edit_id').value = item.id;
        document.getElementById('edit_title').value = item.title;
        document.getElementById('edit_section_type').value = item.section_type;
        document.getElementById('edit_content').value = item.content;
        document.getElementById('edit_sort_order').value = item.sort_order;
        document.getElementById('edit_is_active').value = item.is_active;
        
        const currentFileDiv = document.getElementById('current_file');
        if (item.file_attachment) {
            currentFileDiv.innerHTML = '📎 File saat ini: ' + item.file_attachment;
        } else {
            currentFileDiv.innerHTML = 'Tidak ada file';
        }
        
        toggleModal('editModal');
    }
    </script>

<?php include 'includes/footer.php'; ?>
