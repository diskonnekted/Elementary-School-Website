<?php
$page_title = 'Program Akademik';
require_once 'includes/functions.php';
requireLogin();

require_once 'config/database.php';
require_once 'models/Academic.php';

$database = new Database();
$db = $database->getConnection();
$academic = new Academic($db);

// Handle CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Invalid request token.';
    } else {
        $post_action = $_POST['action'] ?? '';
        
        switch ($post_action) {
            case 'create':
                // Validate data
                $errors = $academic->validate($_POST);
                
                if (empty($errors)) {
                    // Handle file upload
                    $image_path = '';
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                        $file_type = $_FILES['image']['type'];
                        
                        if (in_array($file_type, $allowed_types)) {
                            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                            $filename = 'academic_' . time() . '.' . $file_extension;
                            $upload_path = 'uploads/' . $filename;
                            
                            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                                $image_path = $upload_path;
                            }
                        }
                    }
                    
                    // Set properties
                    $academic->title = $_POST['title'];
                    $academic->description = $_POST['description'];
                    $academic->grade_level = $_POST['grade_level'];
                    $academic->curriculum_type = $_POST['curriculum_type'];
                    $academic->subjects = $_POST['subjects'];
                    $academic->learning_methods = $_POST['learning_methods'];
                    $academic->assessment_methods = $_POST['assessment_methods'];
                    $academic->image = $image_path;
                    $academic->is_active = isset($_POST['is_active']) ? 1 : 0;
                    $academic->sort_order = $_POST['sort_order'] ?? 0;
                    
                    if ($academic->create()) {
                        $message = 'Program akademik berhasil ditambahkan!';
                        $action = 'list'; // Redirect to list after successful creation
                    } else {
                        $error = 'Gagal menambahkan program akademik.';
                    }
                } else {
                    $error = implode('<br>', $errors);
                }
                break;
                
            case 'update':
                if ($id) {
                    // Validate data
                    $errors = $academic->validate($_POST);
                    
                    if (empty($errors)) {
                        // Get existing data
                        $existing_data = $academic->getById($id);
                        $image_path = $existing_data['image'];
                        
                        // Handle file upload
                        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                            $file_type = $_FILES['image']['type'];
                            
                            if (in_array($file_type, $allowed_types)) {
                                $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                                $filename = 'academic_' . time() . '.' . $file_extension;
                                $upload_path = 'uploads/' . $filename;
                                
                                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                                    // Delete old image
                                    if (!empty($image_path) && file_exists($image_path)) {
                                        unlink($image_path);
                                    }
                                    $image_path = $upload_path;
                                }
                            }
                        }
                        
                        // Set properties
                        $academic->id = $id;
                        $academic->title = $_POST['title'];
                        $academic->description = $_POST['description'];
                        $academic->grade_level = $_POST['grade_level'];
                        $academic->curriculum_type = $_POST['curriculum_type'];
                        $academic->subjects = $_POST['subjects'];
                        $academic->learning_methods = $_POST['learning_methods'];
                        $academic->assessment_methods = $_POST['assessment_methods'];
                        $academic->image = $image_path;
                        $academic->is_active = isset($_POST['is_active']) ? 1 : 0;
                        $academic->sort_order = $_POST['sort_order'] ?? 0;
                        
                        if ($academic->update()) {
                            $message = 'Program akademik berhasil diperbarui!';
                            $action = 'list'; // Redirect to list after successful update
                        } else {
                            $error = 'Gagal memperbarui program akademik.';
                        }
                    } else {
                        $error = implode('<br>', $errors);
                    }
                }
                break;
                
            case 'delete':
                if ($id) {
                    // Get existing data to delete image
                    $existing_data = $academic->getById($id);
                    
                    $academic->id = $id;
                    if ($academic->delete()) {
                        // Delete associated image
                        if (!empty($existing_data['image']) && file_exists($existing_data['image'])) {
                            unlink($existing_data['image']);
                        }
                        $message = 'Program akademik berhasil dihapus!';
                    } else {
                        $error = 'Gagal menghapus program akademik.';
                    }
                }
                $action = 'list';
                break;
        }
    }
}

// Get data for list view
$search = $_GET['search'] ?? '';
$grade_level_filter = $_GET['grade_level'] ?? '';
$curriculum_filter = $_GET['curriculum_type'] ?? '';
$page = max(1, $_GET['page'] ?? 1);
$limit = 10;
$offset = ($page - 1) * $limit;

$programs = $academic->getAll($limit, $offset, $search, $grade_level_filter, $curriculum_filter);
$total_programs = $academic->count($search, $grade_level_filter, $curriculum_filter);
$total_pages = ceil($total_programs / $limit);

// Get single program data for edit
$program_data = null;
if ($action === 'edit' && $id) {
    $program_data = $academic->getById($id);
}

require_once 'includes/admin_header.php';
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Program Akademik</h1>
            <p class="mt-2 text-gray-600">Kelola program akademik sekolah</p>
        </div>

        <!-- Messages -->
        <?php if ($message): ?>
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
            <!-- Action Buttons -->
            <div class="mb-6 flex justify-between items-center">
                <a href="?action=create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Tambah Program Baru
                </a>
                <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali ke Dashboard
                </a>
            </div>

            <!-- Search and Filter -->
            <div class="mb-6 bg-white p-4 rounded-lg shadow">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                               placeholder="Cari program..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Kelas</label>
                        <select name="grade_level" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Tingkat</option>
                            <?php foreach (['1', '2', '3', '4', '5', '6', 'semua'] as $grade): ?>
                                <option value="<?php echo $grade; ?>" <?php echo $grade_level_filter === $grade ? 'selected' : ''; ?>>
                                    <?php echo $academic->getGradeLevelName($grade); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kurikulum</label>
                        <select name="curriculum_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Kurikulum</option>
                            <?php foreach (['nasional', 'internasional', 'muatan_lokal'] as $curriculum): ?>
                                <option value="<?php echo $curriculum; ?>" <?php echo $curriculum_filter === $curriculum ? 'selected' : ''; ?>>
                                    <?php echo $academic->getCurriculumTypeName($curriculum); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Filter
                        </button>
                        <a href="academic.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Programs Table -->
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Daftar Program Akademik (<?php echo $total_programs; ?> program)
                    </h3>
                </div>
                
                <?php if (empty($programs)): ?>
                    <div class="text-center py-8">
                        <p class="text-gray-500">Tidak ada program akademik yang ditemukan.</p>
                    </div>
                <?php else: ?>
                    <ul class="divide-y divide-gray-200">
                        <?php foreach ($programs as $program): ?>
                            <li class="px-4 py-6 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <?php if (!empty($program['image'])): ?>
                                            <img class="h-16 w-16 rounded-lg object-cover mr-4" 
                                                 src="<?php echo htmlspecialchars($program['image']); ?>" 
                                                 alt="Program Image">
                                        <?php else: ?>
                                            <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center mr-4">
                                                <span class="text-gray-400 text-xs">No Image</span>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">
                                                <?php echo htmlspecialchars($program['title']); ?>
                                            </h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <?php echo nl2br(htmlspecialchars(substr($program['description'], 0, 150))); ?>
                                                <?php if (strlen($program['description']) > 150): ?>...<?php endif; ?>
                                            </p>
                                            <div class="flex space-x-4 mt-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <?php echo $academic->getGradeLevelName($program['grade_level']); ?>
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <?php echo $academic->getCurriculumTypeName($program['curriculum_type']); ?>
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $program['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                    <?php echo $program['is_active'] ? 'Aktif' : 'Tidak Aktif'; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="?action=edit&id=<?php echo $program['id']; ?>" 
                                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-3 rounded text-sm">
                                            Edit
                                        </a>
                                        <a href="?action=delete&id=<?php echo $program['id']; ?>" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus program ini?')"
                                           class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded text-sm">
                                            Hapus
                                        </a>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="mt-6 flex items-center justify-between">
                    <div class="flex-1 flex justify-center">
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&grade_level=<?php echo urlencode($grade_level_filter); ?>&curriculum_type=<?php echo urlencode($curriculum_filter); ?>" 
                                   class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    Sebelumnya
                                </a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&grade_level=<?php echo urlencode($grade_level_filter); ?>&curriculum_type=<?php echo urlencode($curriculum_filter); ?>" 
                                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo $i === $page ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50'; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&grade_level=<?php echo urlencode($grade_level_filter); ?>&curriculum_type=<?php echo urlencode($curriculum_filter); ?>" 
                                   class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    Selanjutnya
                                </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
            <?php endif; ?>

        <?php elseif ($action === 'create' || $action === 'edit'): ?>
            
            <!-- Form -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <?php echo $action === 'create' ? 'Tambah Program Baru' : 'Edit Program'; ?>
                    </h3>
                    
                    <form method="POST" enctype="multipart/form-data" class="space-y-6">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="action" value="<?php echo $action; ?>">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Judul Program *</label>
                            <input type="text" name="title" 
                                   value="<?php echo htmlspecialchars($program_data['title'] ?? ''); ?>" 
                                   required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deskripsi *</label>
                            <textarea name="description" rows="4" required
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"><?php echo htmlspecialchars($program_data['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tingkat Kelas *</label>
                                <select name="grade_level" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Tingkat Kelas</option>
                                    <?php foreach (['1', '2', '3', '4', '5', '6', 'semua'] as $grade): ?>
                                        <option value="<?php echo $grade; ?>" 
                                                <?php echo ($program_data['grade_level'] ?? '') === $grade ? 'selected' : ''; ?>>
                                            <?php echo $academic->getGradeLevelName($grade); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Kurikulum</label>
                                <select name="curriculum_type"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Kurikulum</option>
                                    <?php foreach (['nasional', 'internasional', 'muatan_lokal'] as $curriculum): ?>
                                        <option value="<?php echo $curriculum; ?>" 
                                                <?php echo ($program_data['curriculum_type'] ?? '') === $curriculum ? 'selected' : ''; ?>>
                                            <?php echo $academic->getCurriculumTypeName($curriculum); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                            <textarea name="subjects" rows="3" 
                                      placeholder='Contoh: ["Matematika", "Bahasa Indonesia", "IPA", "IPS"]'
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"><?php echo htmlspecialchars($program_data['subjects'] ?? ''); ?></textarea>
                            <p class="mt-1 text-sm text-gray-500">Format JSON array. Contoh: ["Matematika", "Bahasa Indonesia"]</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Metode Pembelajaran</label>
                            <textarea name="learning_methods" rows="3" 
                                      placeholder='Contoh: ["Ceramah", "Diskusi", "Praktikum", "Project Based Learning"]'
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"><?php echo htmlspecialchars($program_data['learning_methods'] ?? ''); ?></textarea>
                            <p class="mt-1 text-sm text-gray-500">Format JSON array. Contoh: ["Ceramah", "Diskusi"]</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Metode Penilaian</label>
                            <textarea name="assessment_methods" rows="3" 
                                      placeholder='Contoh: ["Ujian Tulis", "Ujian Lisan", "Tugas", "Portofolio"]'
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"><?php echo htmlspecialchars($program_data['assessment_methods'] ?? ''); ?></textarea>
                            <p class="mt-1 text-sm text-gray-500">Format JSON array. Contoh: ["Ujian Tulis", "Tugas"]</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gambar Program</label>
                            <input type="file" name="image" accept="image/*"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <?php if (!empty($program_data['image'])): ?>
                                <div class="mt-2">
                                    <img src="<?php echo htmlspecialchars($program_data['image']); ?>" 
                                         alt="Current image" class="h-32 w-auto rounded">
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Urutan Tampilan</label>
                                <input type="number" name="sort_order" min="0"
                                       value="<?php echo htmlspecialchars($program_data['sort_order'] ?? 0); ?>"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" 
                                       <?php echo ($program_data['is_active'] ?? 1) ? 'checked' : ''; ?>
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label class="ml-2 block text-sm text-gray-900">Program Aktif</label>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <a href="academic.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <?php echo $action === 'create' ? 'Tambah Program' : 'Update Program'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        <?php endif; ?>
        
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
