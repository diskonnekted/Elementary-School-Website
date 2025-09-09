<?php
require_once 'admin/config/database.php';
require_once 'admin/models/Transparency.php';
require_once 'includes/settings.php';

// Get school info from database settings
$school_info = getSchoolInfo();
$contact_info = getContactInfo();
$social_media = getSocialMedia();

try {
    $database = new Database();
    $db = $database->getConnection();
    $transparency = new Transparency($db);
    
    // Get parameters
    $section_filter = $_GET['section'] ?? '';
    $search_query = $_GET['search'] ?? '';
    
    // Get all active transparency data
    $all_transparencies = $transparency->getAll($section_filter);
    
    // Apply search if provided
    if (!empty($search_query)) {
        $all_transparencies = array_filter($all_transparencies, function($item) use ($search_query) {
            return stripos($item['title'], $search_query) !== false || 
                   stripos($item['content'], $search_query) !== false;
        });
    }
    
    // Group by section type
    $grouped_data = [];
    foreach ($all_transparencies as $item) {
        $grouped_data[$item['section_type']][] = $item;
    }
    
    // Get statistics
    $stats = $transparency->getStats();
    
    // Section type names and icons
    $section_info = [
        'financial' => ['name' => 'Laporan Keuangan', 'icon' => 'fas fa-chart-pie', 'color' => '#6366f1'],
        'budget' => ['name' => 'Anggaran Sekolah', 'icon' => 'fas fa-calculator', 'color' => '#8b5cf6'],
        'governance' => ['name' => 'Tata Kelola', 'icon' => 'fas fa-users-cog', 'color' => '#06b6d4'],
        'reports' => ['name' => 'Laporan Berkala', 'icon' => 'fas fa-file-alt', 'color' => '#10b981'],
        'policies' => ['name' => 'Kebijakan', 'icon' => 'fas fa-balance-scale', 'color' => '#f59e0b'],
        'procurement' => ['name' => 'Pengadaan', 'icon' => 'fas fa-shopping-cart', 'color' => '#ef4444'],
        'other' => ['name' => 'Lainnya', 'icon' => 'fas fa-folder', 'color' => '#6b7280']
    ];
    
} catch (Exception $e) {
    error_log("Error in transparansi.php: " . $e->getMessage());
    $all_transparencies = [];
    $grouped_data = [];
    $stats = ['total' => 0, 'active' => 0];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transparansi - <?php echo htmlspecialchars($school_info['name']); ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <!-- Header & Navigation -->
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <i class="fas fa-graduation-cap"></i>
                    <span><?php echo htmlspecialchars($school_info['name']); ?></span>
                </div>
                
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a href="profil.php" class="nav-link">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a href="berita.php" class="nav-link">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a href="academic.php" class="nav-link">Akademik</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="info.php" class="nav-link dropdown-toggle active">Info</a>
                        <ul class="dropdown-menu">
                            <li><a href="info.php">Informasi Umum</a></li>
                            <li><a href="transparansi.php">Transparansi</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="inovasi.php" class="nav-link">Inovasi</a>
                    </li>
                    <li class="nav-item">
                        <a href="contact.php" class="nav-link">Kontak</a>
                    </li>
                </ul>
                
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
    </header>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1>Transparansi Sekolah</h1>
                <p>Keterbukaan informasi dan akuntabilitas untuk kepercayaan publik</p>
                <nav class="breadcrumb">
                    <a href="index.php">Beranda</a>
                    <span>/</span>
                    <a href="info.php">Info</a>
                    <span>/</span>
                    <span>Transparansi</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Search & Filter Section -->
    <section class="py-8 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <form method="GET" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-64">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search mr-2"></i>Cari Informasi
                            </label>
                            <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" 
                                   placeholder="Cari laporan, dokumen, atau informasi..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="min-w-48">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-filter mr-2"></i>Kategori
                            </label>
                            <select name="section" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($section_info as $type => $info): ?>
                                    <option value="<?php echo $type; ?>" <?php echo $section_filter === $type ? 'selected' : ''; ?>>
                                        <?php echo $info['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-search mr-2"></i>Cari
                            </button>
                        </div>
                        
                        <?php if (!empty($search_query) || !empty($section_filter)): ?>
                        <div>
                            <a href="transparansi.php" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg border border-gray-300 transition-colors">
                                <i class="fas fa-times mr-2"></i>Reset
                            </a>
                        </div>
                        <?php endif; ?>
                    </form>
                    
                    <?php if (!empty($search_query) || !empty($section_filter)): ?>
                    <div class="mt-4 text-sm text-gray-600">
                        Menampilkan <?php echo count($all_transparencies); ?> hasil
                        <?php if (!empty($search_query)): ?>
                            untuk "<strong><?php echo htmlspecialchars($search_query); ?></strong>"
                        <?php endif; ?>
                        <?php if (!empty($section_filter)): ?>
                            dalam kategori <strong><?php echo $section_info[$section_filter]['name'] ?? $section_filter; ?></strong>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Overview -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Ringkasan Transparansi</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Komitmen kami untuk memberikan informasi yang terbuka dan dapat dipertanggungjawabkan</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 bg-blue-50 rounded-lg">
                    <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-blue-900 mb-2"><?php echo $stats['active'] ?? 0; ?></h3>
                    <p class="text-blue-700">Dokumen Aktif</p>
                </div>
                
                <div class="text-center p-6 bg-green-50 rounded-lg">
                    <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-green-900 mb-2"><?php echo count($section_info); ?></h3>
                    <p class="text-green-700">Kategori Informasi</p>
                </div>
                
                <div class="text-center p-6 bg-purple-50 rounded-lg">
                    <div class="w-16 h-16 mx-auto mb-4 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-purple-900 mb-2">24/7</h3>
                    <p class="text-purple-700">Akses Online</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Transparency Content by Categories -->
    <?php if (empty($all_transparencies)): ?>
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gray-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-search text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak Ada Data</h3>
                    <p class="text-gray-600">
                        <?php if (!empty($search_query) || !empty($section_filter)): ?>
                            Tidak ditemukan hasil yang sesuai dengan pencarian Anda.
                        <?php else: ?>
                            Belum ada informasi transparansi yang tersedia saat ini.
                        <?php endif; ?>
                    </p>
                    <?php if (!empty($search_query) || !empty($section_filter)): ?>
                    <a href="transparansi.php" class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                        Lihat semua informasi →
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php else: ?>
        <?php foreach ($grouped_data as $section_type => $items): ?>
        <?php $info = $section_info[$section_type] ?? ['name' => ucfirst($section_type), 'icon' => 'fas fa-folder', 'color' => '#6b7280']; ?>
        
        <section class="py-12 <?php echo array_search($section_type, array_keys($grouped_data)) % 2 === 0 ? 'bg-white' : 'bg-gray-50'; ?>">
            <div class="container mx-auto px-4">
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 mr-4 rounded-lg flex items-center justify-center" style="background-color: <?php echo $info['color']; ?>20;">
                            <i class="<?php echo $info['icon']; ?> text-2xl" style="color: <?php echo $info['color']; ?>;"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900"><?php echo $info['name']; ?></h2>
                            <p class="text-gray-600"><?php echo count($items); ?> dokumen tersedia</p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <?php foreach ($items as $item): ?>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 leading-tight">
                                    <?php echo htmlspecialchars($item['title']); ?>
                                </h3>
                                <?php if ($item['file_attachment']): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-paperclip mr-1"></i>File
                                </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="text-gray-600 mb-4 line-clamp-3">
                                <?php 
                                $content_preview = strip_tags($item['content']);
                                echo htmlspecialchars(strlen($content_preview) > 200 ? substr($content_preview, 0, 200) . '...' : $content_preview);
                                ?>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    <?php echo date('d F Y', strtotime($item['created_at'])); ?>
                                </span>
                                
                                <div class="flex space-x-2">
                                    <button onclick="viewTransparencyDetail(<?php echo $item['id']; ?>)" 
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        <i class="fas fa-eye mr-1"></i>Lihat Detail
                                    </button>
                                    
                                    <?php if ($item['file_attachment']): ?>
                                    <a href="admin/uploads/attachments/<?php echo htmlspecialchars($item['file_attachment']); ?>" 
                                       target="_blank" download
                                       class="text-green-600 hover:text-green-800 text-sm font-medium">
                                        <i class="fas fa-download mr-1"></i>Unduh
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Contact for Transparency -->
    <section class="py-16 bg-blue-600 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h3 class="text-3xl font-bold mb-6">Butuh Informasi Lebih Lanjut?</h3>
                        <p class="text-blue-100 mb-8 text-lg">Kami berkomitmen untuk memberikan informasi yang transparan dan mudah diakses. Hubungi kami untuk mendapatkan dokumen atau penjelasan lebih detail.</p>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <i class="fas fa-user-tie text-blue-200 text-xl mr-4 w-6"></i>
                                <div>
                                    <div class="font-semibold">Koordinator Transparansi</div>
                                    <div class="text-blue-100">Dra. Siti Nurlaela, M.Pd</div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-blue-200 text-xl mr-4 w-6"></i>
                                <div>
                                    <div class="font-semibold">Email</div>
                                    <div class="text-blue-100"><?php echo htmlspecialchars($contact_info['email']); ?></div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-blue-200 text-xl mr-4 w-6"></i>
                                <div>
                                    <div class="font-semibold">Telepon</div>
                                    <div class="text-blue-100"><?php echo htmlspecialchars($contact_info['phone']); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg p-8 text-gray-900">
                        <h4 class="text-xl font-semibold mb-6">Ajukan Pertanyaan</h4>
                        <form class="space-y-4" id="transparencyForm">
                            <div>
                                <input type="text" name="name" placeholder="Nama Lengkap" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <input type="email" name="email" placeholder="Email" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <select name="category" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Kategori Informasi</option>
                                    <option value="financial">Laporan Keuangan</option>
                                    <option value="budget">Anggaran Sekolah</option>
                                    <option value="governance">Tata Kelola</option>
                                    <option value="reports">Laporan Berkala</option>
                                    <option value="policies">Kebijakan</option>
                                    <option value="other">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <textarea name="message" rows="4" placeholder="Pertanyaan atau informasi yang dibutuhkan" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Pertanyaan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-graduation-cap"></i>
                        <span><?php echo htmlspecialchars($school_info['name']); ?></span>
                    </div>
                    <p><?php echo htmlspecialchars($school_info['description']); ?></p>
                    <div class="social-links">
                        <a href="<?php echo htmlspecialchars($social_media['facebook']); ?>"><i class="fab fa-facebook"></i></a>
                        <a href="<?php echo htmlspecialchars($social_media['instagram']); ?>"><i class="fab fa-instagram"></i></a>
                        <a href="<?php echo htmlspecialchars($social_media['youtube']); ?>"><i class="fab fa-youtube"></i></a>
                        <a href="<?php echo htmlspecialchars($social_media['whatsapp']); ?>"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Menu Utama</h3>
                    <ul>
                        <li><a href="profil.html">Profil</a></li>
                        <li><a href="berita.php">Berita</a></li>
                        <li><a href="akademik.php">Akademik</a></li>
                        <li><a href="inovasi.php">Inovasi</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Informasi</h3>
                    <ul>
                        <li><a href="info.php">Informasi Umum</a></li>
                        <li><a href="transparansi.php">Transparansi</a></li>
                        <li><a href="kontak.html">Kontak</a></li>
                        <li><a href="pendidikan-karakter.html">Pendidikan Karakter</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Kontak</h3>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($contact_info['address']); ?></li>
                        <li><i class="fas fa-phone"></i> <?php echo htmlspecialchars($contact_info['phone']); ?></li>
                        <li><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($contact_info['email']); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="relative bg-white rounded-lg max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Detail Informasi</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="p-6" id="modalContent">
                    <div class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i>
                        <p class="mt-2 text-gray-600">Memuat...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.querySelector('.hamburger').addEventListener('click', function() {
            document.querySelector('.nav-menu').classList.toggle('active');
            this.classList.toggle('active');
        });

        // View transparency detail
        function viewTransparencyDetail(id) {
            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('modalContent').innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i>
                    <p class="mt-2 text-gray-600">Memuat...</p>
                </div>
            `;
            
            fetch(`transparansi_api.php?action=view&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const item = data.data;
                        document.getElementById('modalTitle').textContent = item.title;
                        document.getElementById('modalContent').innerHTML = `
                            <div class="space-y-6">
                                <div class="flex flex-wrap items-center gap-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-tag mr-2"></i>${item.section_name}
                                    </span>
                                    <span class="text-gray-500">
                                        <i class="fas fa-calendar mr-2"></i>${item.formatted_date}
                                    </span>
                                    ${item.has_file ? `
                                    <a href="${item.file_url}" target="_blank" download 
                                       class="inline-flex items-center text-green-600 hover:text-green-800">
                                        <i class="fas fa-download mr-2"></i>Unduh File
                                    </a>
                                    ` : ''}
                                </div>
                                
                                <div class="prose max-w-none">
                                    <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                                        ${item.content}
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        document.getElementById('modalContent').innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                                <p class="mt-2 text-gray-600">${data.message || 'Terjadi kesalahan'}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    document.getElementById('modalContent').innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                            <p class="mt-2 text-gray-600">Terjadi kesalahan saat memuat data</p>
                        </div>
                    `;
                });
        }

        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Close modal on ESC key or outside click
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Form submission
        document.getElementById('transparencyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Add form submission logic here
            alert('Terima kasih! Pertanyaan Anda akan segera kami respons.');
            this.reset();
        });
    </script>
</body>
</html>
