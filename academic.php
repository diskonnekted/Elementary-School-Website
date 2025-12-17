<?php
// Include necessary files
include_once 'includes/settings.php';

// Get school info
$school_info = getSchoolInfo();
$contact_info = getContactInfo();

// Set page title
$page_title = "Program Akademik - " . $school_info['name'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <?php include 'includes/favicon.php'; ?>

    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Header & Navigation -->
    <header class="header sticky top-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm">
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <i class="fas fa-graduation-cap text-2xl text-blue-600"></i>
                    <span class="text-xl font-bold"><?php echo htmlspecialchars($school_info['name']); ?></span>
                </div>
                
                <ul class="nav-menu">
                    <li class="nav-item"><a href="index.php" class="nav-link">Beranda</a></li>
                    <li class="nav-item"><a href="profil.php" class="nav-link">Profil</a></li>
                    <li class="nav-item"><a href="berita.php" class="nav-link">Berita</a></li>
                    <li class="nav-item"><a href="academic.php" class="nav-link active">Akademik</a></li>
                    <li class="nav-item dropdown">
                        <a href="info.php" class="nav-link dropdown-toggle">Info</a>
                        <ul class="dropdown-menu">
                            <li><a href="info.php">Informasi Umum</a></li>
                            <li><a href="transparansi.php">Transparansi</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a href="inovasi.php" class="nav-link">Inovasi</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Kontak</a></li>
                </ul>
                
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-green-600 via-blue-600 to-indigo-800 py-20 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0">
            <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute top-40 right-20 w-32 h-32 bg-green-300/20 rounded-full blur-2xl"></div>
            <div class="absolute bottom-20 left-1/3 w-24 h-24 bg-blue-300/20 rounded-full blur-xl"></div>
        </div>
        
        <div class="relative container text-center text-white z-10">
            <div class="mb-6">
                <span class="inline-flex items-center bg-white/20 backdrop-blur px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-book mr-2"></i>
                    Program Akademik
                </span>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                Akademik <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">Berkualitas</span>
            </h1>
            <p class="text-xl md:text-2xl opacity-90 mb-10 max-w-3xl mx-auto">
                Program pembelajaran yang komprehensif dan inovatif untuk mengembangkan potensi setiap siswa
            </p>
        </div>
        
        <!-- Wave separator -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden">
            <svg class="relative block w-full h-20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" fill="#f9fafb"></path>
            </svg>
        </div>
    </section>

    <!-- Academic Content -->
    <section class="py-16">
        <div class="container">
            <!-- Loading State -->
            <div id="loading" class="text-center py-20">
                <div class="relative inline-block">
                    <div class="animate-spin rounded-full h-20 w-20 border-t-4 border-blue-500 border-opacity-25"></div>
                    <div class="animate-ping absolute top-0 left-0 h-20 w-20 rounded-full bg-blue-400 opacity-20"></div>
                </div>
                <h3 class="mt-6 text-xl font-semibold text-gray-900">Memuat Data Akademik</h3>
                <p class="mt-2 text-gray-600">Sedang mengambil informasi program akademik...</p>
            </div>

            <!-- Error State -->
            <div id="error" class="text-center py-20 hidden">
                <div class="max-w-md mx-auto">
                    <div class="bg-red-100 rounded-full p-8 w-32 h-32 mx-auto mb-8">
                        <i class="fas fa-exclamation-triangle text-5xl text-red-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Oops! Terjadi Kesalahan</h3>
                    <p class="text-gray-600 mb-8">Gagal memuat data akademik. Periksa koneksi internet Anda dan coba lagi.</p>
                    <button onclick="loadAcademicData()" 
                            class="px-8 py-4 bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-bold rounded-2xl transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-redo mr-2"></i>Coba Lagi
                    </button>
                </div>
            </div>

            <!-- Academic Content -->
            <div id="academicContent" class="hidden">
                <div id="academicData"></div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="js/script.js"></script>
    <script>
    // Academic API Integration
    const API_URL = '/api/academic.php';

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        loadAcademicData();
    });

    // Load academic data
    async function loadAcademicData() {
        showLoading();
        hideError();
        
        try {
            const response = await fetch(API_URL);
            const data = await response.json();
            
            if (data.success) {
                displayAcademicData(data.data);
                showContent();
            } else {
                throw new Error(data.message || 'Failed to load academic data');
            }
        } catch (error) {
            console.error('Error loading academic data:', error);
            showError();
        }
    }

    // Display academic data
    function displayAcademicData(data) {
        const container = document.getElementById('academicData');
        
        container.innerHTML = `
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Program Akademik Kami</h2>
                    <p class="text-gray-600 text-xl max-w-2xl mx-auto">
                        Kurikulum yang dirancang untuk mengembangkan kemampuan akademik dan karakter siswa
                    </p>
                </div>

                <!-- Academic Programs Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                    ${data.map(item => `
                        <div class="bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden group">
                            <div class="relative h-48 bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                                <div class="text-white text-center">
                                    <i class="fas fa-book text-5xl mb-4 opacity-90"></i>
                                    <h3 class="text-2xl font-bold">${item.title}</h3>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            </div>
                            <div class="p-8">
                                <p class="text-gray-600 leading-relaxed mb-6">${item.description}</p>
                                <div class="space-y-3">
                                    ${item.details ? item.details.map(detail => `
                                        <div class="flex items-center text-sm text-gray-700">
                                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                            ${detail}
                                        </div>
                                    `).join('') : ''}
                                </div>
                                ${item.schedule ? `
                                    <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                                        <h4 class="font-semibold text-gray-900 mb-2">
                                            <i class="fas fa-clock mr-2 text-blue-500"></i>Jadwal
                                        </h4>
                                        <p class="text-gray-600 text-sm">${item.schedule}</p>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `).join('')}
                </div>

                <!-- Additional Info -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl p-12 text-center text-white">
                    <h3 class="text-3xl font-bold mb-6">Mengapa Memilih Program Akademik Kami?</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                        <div class="text-center">
                            <i class="fas fa-medal text-4xl mb-4 opacity-90"></i>
                            <h4 class="text-xl font-semibold mb-2">Berkualitas Tinggi</h4>
                            <p class="opacity-90">Kurikulum yang telah teruji dan sesuai standar nasional</p>
                        </div>
                        <div class="text-center">
                            <i class="fas fa-users text-4xl mb-4 opacity-90"></i>
                            <h4 class="text-xl font-semibold mb-2">Pengajar Berpengalaman</h4>
                            <p class="opacity-90">Tim pengajar yang profesional dan berdedikasi</p>
                        </div>
                        <div class="text-center">
                            <i class="fas fa-lightbulb text-4xl mb-4 opacity-90"></i>
                            <h4 class="text-xl font-semibold mb-2">Metode Inovatif</h4>
                            <p class="opacity-90">Pembelajaran yang menyenangkan dan efektif</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Show/hide states
    function showLoading() {
        document.getElementById('loading').classList.remove('hidden');
        document.getElementById('academicContent').classList.add('hidden');
    }

    function showContent() {
        document.getElementById('loading').classList.add('hidden');
        document.getElementById('academicContent').classList.remove('hidden');
    }

    function showError() {
        document.getElementById('loading').classList.add('hidden');
        document.getElementById('error').classList.remove('hidden');
        document.getElementById('academicContent').classList.add('hidden');
    }

    function hideError() {
        document.getElementById('error').classList.add('hidden');
    }
    </script>

    <!-- Custom styles -->
    <style>
    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    </style>
</body>
</html>
