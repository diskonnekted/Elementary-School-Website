<?php
$page_title = 'Program Akademik';
require_once 'includes/auth.php';
require_once 'includes/functions.php';
Auth::requireLogin();

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
$id = $_GET['id'] ?? $_POST['id'] ?? null;
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Invalid request token.';
    } else {
        $post_action = $_POST['action'] ?? '';
        
        // Helper to process JSON fields
        $processJsonField = function($input) {
            if (empty($input)) return '[]';
            // Check if already valid JSON
            json_decode($input);
            if (json_last_error() === JSON_ERROR_NONE) return $input;
            // Convert comma-separated to JSON
            $array = array_filter(array_map('trim', explode(',', $input)));
            return json_encode(array_values($array));
        };

        // Pre-process JSON fields
        $json_fields = ['subjects', 'learning_methods', 'assessment_methods'];
        foreach ($json_fields as $field) {
            if (isset($_POST[$field])) {
                $_POST[$field] = $processJsonField($_POST[$field]);
            }
        }
        
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
                        Auth::setFlashMessage('success', 'Program akademik berhasil ditambahkan!');
                        header('Location: academic.php');
                        exit;
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
                            Auth::setFlashMessage('success', 'Program akademik berhasil diperbarui!');
                            header('Location: academic.php');
                            exit;
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
                        Auth::setFlashMessage('success', 'Program akademik berhasil dihapus!');
                    } else {
                        Auth::setFlashMessage('error', 'Gagal menghapus program akademik.');
                    }
                }
                header('Location: academic.php');
                exit;
                break;
        }
    }
}

// Handle DELETE via GET
if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $csrf_token = $_GET['csrf_token'] ?? '';
    if ($csrf_token === $_SESSION['csrf_token']) {
        if ($id) {
            $existing_data = $academic->getById($id);
            $academic->id = $id;
            if ($academic->delete()) {
                if (!empty($existing_data['image']) && file_exists($existing_data['image'])) {
                    unlink($existing_data['image']);
                }
                Auth::setFlashMessage('success', 'Program akademik berhasil dihapus!');
            } else {
                Auth::setFlashMessage('error', 'Gagal menghapus program akademik.');
            }
        } else {
             Auth::setFlashMessage('error', 'ID program tidak valid.');
        }
    } else {
        Auth::setFlashMessage('error', 'Token CSRF tidak valid!');
    }
    header('Location: academic.php');
    exit;
}

// Handle AJAX request for single program data
if ($action === 'get_academic' && $id) {
    $program_data = $academic->getById($id);
    header('Content-Type: application/json');
    echo json_encode($program_data);
    exit;
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

require_once 'includes/admin_header.php';
?>

<div class="space-y-6">
    <?php if ($error): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline"><?php echo $error; ?></span>
    </div>
    <?php endif; ?>
                <!-- Header Actions -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Program Akademik</h2>
                        <p class="text-gray-600">Kelola program akademik sekolah</p>
                    </div>
                    <div class="mt-4 sm:mt-0 flex space-x-3">
                        <button onclick="openCreateModal()" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>Tambah Program
                        </button>
                        <a href="index.php" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                                   placeholder="Cari program..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kelas</label>
                            <select name="grade_level" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">Semua Tingkat</option>
                                <?php foreach (['1', '2', '3', '4', '5', '6', 'semua'] as $grade): ?>
                                    <option value="<?php echo $grade; ?>" <?php echo $grade_level_filter === $grade ? 'selected' : ''; ?>>
                                        <?php echo $academic->getGradeLevelName($grade); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kurikulum</label>
                            <select name="curriculum_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">Semua Kurikulum</option>
                                <?php foreach (['nasional', 'internasional', 'muatan_lokal'] as $curriculum): ?>
                                    <option value="<?php echo $curriculum; ?>" <?php echo $curriculum_filter === $curriculum ? 'selected' : ''; ?>>
                                        <?php echo $academic->getCurriculumTypeName($curriculum); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                            <a href="academic.php" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Programs List -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
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
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-50 text-primary-700">
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
                                        <button onclick="openEditModal(<?php echo $program['id']; ?>)" 
                                           class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-3 rounded-lg text-sm transition-colors">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        <a href="?action=delete&id=<?php echo $program['id']; ?>&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus program ini?')"
                                           class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-3 rounded-lg text-sm transition-colors">
                                            <i class="fas fa-trash mr-1"></i> Hapus
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
                                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo $i === $page ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50'; ?>">
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
            
    </div>
</div>

<?php include 'includes/academic_modals.php'; ?>
<?php include 'includes/admin_footer.php'; ?>
