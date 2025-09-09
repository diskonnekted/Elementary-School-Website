<?php
// Include necessary files
include_once 'includes/settings.php';

// Get school info
$school_info = getSchoolInfo();
$contact_info = getContactInfo();

// Set page title
$page_title = "Hubungi Kami - " . $school_info['name'];
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
                    <li class="nav-item"><a href="academic.php" class="nav-link">Akademik</a></li>
                    <li class="nav-item dropdown">
                        <a href="info.php" class="nav-link dropdown-toggle">Info</a>
                        <ul class="dropdown-menu">
                            <li><a href="info.php">Informasi Umum</a></li>
                            <li><a href="transparansi.php">Transparansi</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a href="inovasi.php" class="nav-link">Inovasi</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link active">Kontak</a></li>
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
    <section class="relative bg-gradient-to-br from-purple-600 via-blue-600 to-indigo-800 py-20 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0">
            <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute top-40 right-20 w-32 h-32 bg-purple-300/20 rounded-full blur-2xl"></div>
            <div class="absolute bottom-20 left-1/3 w-24 h-24 bg-blue-300/20 rounded-full blur-xl"></div>
        </div>
        
        <div class="relative container text-center text-white z-10">
            <div class="mb-6">
                <span class="inline-flex items-center bg-white/20 backdrop-blur px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-phone mr-2"></i>
                    Hubungi Kami
                </span>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                Kontak <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">& Lokasi</span>
            </h1>
            <p class="text-xl md:text-2xl opacity-90 mb-10 max-w-3xl mx-auto">
                Kami siap membantu dan menjawab pertanyaan Anda. Jangan ragu untuk menghubungi kami!
            </p>
        </div>
        
        <!-- Wave separator -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden">
            <svg class="relative block w-full h-20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" fill="#f9fafb"></path>
            </svg>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="py-16">
        <div class="container">
            <div class="max-w-6xl mx-auto">
                <!-- Contact Info -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
                    <div class="bg-white rounded-3xl shadow-xl p-8 text-center group hover:shadow-2xl transition-all duration-500">
                        <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-200 transition-colors">
                            <i class="fas fa-map-marker-alt text-3xl text-blue-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Alamat</h3>
                        <p class="text-gray-600 leading-relaxed">
                            <?php echo nl2br(htmlspecialchars($contact_info['address'] ?: 'Alamat belum diatur')); ?>
                        </p>
                    </div>
                    
                    <div class="bg-white rounded-3xl shadow-xl p-8 text-center group hover:shadow-2xl transition-all duration-500">
                        <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-green-200 transition-colors">
                            <i class="fas fa-phone text-3xl text-green-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Telepon</h3>
                        <p class="text-gray-600 leading-relaxed">
                            <strong>Telepon:</strong> <?php echo htmlspecialchars($contact_info['phone'] ?: 'Belum diatur'); ?><br>
                            <strong>Jam Operasional:</strong> <?php echo htmlspecialchars($contact_info['operating_hours']); ?>
                        </p>
                    </div>
                    
                    <div class="bg-white rounded-3xl shadow-xl p-8 text-center group hover:shadow-2xl transition-all duration-500">
                        <div class="bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-purple-200 transition-colors">
                            <i class="fas fa-envelope text-3xl text-purple-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Email</h3>
                        <p class="text-gray-600 leading-relaxed">
                            <strong>Email:</strong> <?php echo htmlspecialchars($contact_info['email'] ?: 'Belum diatur'); ?><br>
                            <?php if (!empty($school_info['website'])): ?>
                            <strong>Website:</strong> <a href="<?php echo htmlspecialchars($school_info['website']); ?>" target="_blank" class="text-blue-600 hover:text-blue-800"><?php echo htmlspecialchars($school_info['website']); ?></a>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <!-- Contact Form & Office Hours -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Contact Form -->
                    <div class="bg-white rounded-3xl shadow-xl p-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">Kirim Pesan</h2>
                        <form id="contactForm" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-user mr-2 text-blue-500"></i>Nama Lengkap
                                    </label>
                                    <input type="text" id="name" name="name" required
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-envelope mr-2 text-green-500"></i>Email
                                    </label>
                                    <input type="email" id="email" name="email" required
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-phone mr-2 text-purple-500"></i>Nomor Telepon
                                </label>
                                <input type="tel" id="phone" name="phone"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-tag mr-2 text-orange-500"></i>Subjek
                                </label>
                                <input type="text" id="subject" name="subject" required
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-comment mr-2 text-red-500"></i>Pesan
                                </label>
                                <textarea id="message" name="message" rows="5" required
                                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all resize-none"></textarea>
                            </div>
                            
                            <button type="submit"
                                    class="w-full px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan
                            </button>
                        </form>
                        
                        <!-- Success/Error Messages -->
                        <div id="success" class="mt-6 p-4 bg-green-100 border border-green-300 rounded-xl text-green-700 hidden">
                            <i class="fas fa-check-circle mr-2"></i>Pesan berhasil dikirim! Kami akan segera membalas.
                        </div>
                        <div id="error" class="mt-6 p-4 bg-red-100 border border-red-300 rounded-xl text-red-700 hidden">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Terjadi kesalahan. Silakan coba lagi.
                        </div>
                    </div>

                    <!-- Office Hours & Social Media -->
                    <div class="space-y-8">
                        <!-- Office Hours -->
                        <div class="bg-white rounded-3xl shadow-xl p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">
                                <i class="fas fa-clock mr-3 text-blue-500"></i>Jam Operasional
                            </h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="font-semibold text-gray-700">Senin - Jumat</span>
                                    <span class="text-gray-600">07:00 - 16:00</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="font-semibold text-gray-700">Sabtu</span>
                                    <span class="text-gray-600">07:00 - 12:00</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="font-semibold text-gray-700">Minggu</span>
                                    <span class="text-red-500 font-semibold">Tutup</span>
                                </div>
                            </div>
                            <div class="mt-6 p-4 bg-blue-50 rounded-xl">
                                <p class="text-sm text-blue-800">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Untuk kunjungan, mohon membuat janji terlebih dahulu melalui telepon atau email.
                                </p>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="bg-white rounded-3xl shadow-xl p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">
                                <i class="fas fa-share-alt mr-3 text-green-500"></i>Media Sosial
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <a href="#" class="flex items-center p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors group">
                                    <div class="bg-blue-500 p-3 rounded-full group-hover:scale-110 transition-transform">
                                        <i class="fab fa-facebook-f text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-semibold text-gray-900">Facebook</p>
                                        <p class="text-sm text-gray-600">@SDCerdasCeria</p>
                                    </div>
                                </a>
                                
                                <a href="#" class="flex items-center p-4 bg-pink-50 rounded-xl hover:bg-pink-100 transition-colors group">
                                    <div class="bg-pink-500 p-3 rounded-full group-hover:scale-110 transition-transform">
                                        <i class="fab fa-instagram text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-semibold text-gray-900">Instagram</p>
                                        <p class="text-sm text-gray-600">@sdcerdasceria</p>
                                    </div>
                                </a>
                                
                                <a href="#" class="flex items-center p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors group">
                                    <div class="bg-blue-400 p-3 rounded-full group-hover:scale-110 transition-transform">
                                        <i class="fab fa-twitter text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-semibold text-gray-900">Twitter</p>
                                        <p class="text-sm text-gray-600">@SDCerdasCeria</p>
                                    </div>
                                </a>
                                
                                <a href="#" class="flex items-center p-4 bg-red-50 rounded-xl hover:bg-red-100 transition-colors group">
                                    <div class="bg-red-500 p-3 rounded-full group-hover:scale-110 transition-transform">
                                        <i class="fab fa-youtube text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-semibold text-gray-900">YouTube</p>
                                        <p class="text-sm text-gray-600">SD Cerdas Ceria</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Placeholder -->
                <div class="mt-16 bg-white rounded-3xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-map mr-3 text-red-500"></i>Lokasi Sekolah
                    </h3>
                    <div class="bg-gray-100 rounded-2xl h-96 flex items-center justify-center">
                        <div class="text-center text-gray-500">
                            <i class="fas fa-map-marked-alt text-6xl mb-4"></i>
                            <p class="text-xl font-semibold mb-2">Peta Lokasi</p>
                            <p>Jl. Pendidikan No. 123, Jakarta Pusat</p>
                            <button onclick="openMaps()" class="mt-4 px-6 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors">
                                <i class="fas fa-external-link-alt mr-2"></i>Buka di Google Maps
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="lg:col-span-2">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-graduation-cap text-3xl text-blue-400 mr-4"></i>
                        <div>
                            <h3 class="text-2xl font-bold">SD Cerdas Ceria</h3>
                            <p class="text-gray-400">Hubungi Kami</p>
                        </div>
                    </div>
                    <p class="text-gray-300 leading-relaxed mb-6">
                        Kami selalu terbuka untuk pertanyaan, saran, dan masukan dari orang tua siswa dan masyarakat.
                        Mari bersama-sama membangun pendidikan yang berkualitas.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-blue-600 p-3 rounded-full hover:bg-blue-700 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="bg-pink-600 p-3 rounded-full hover:bg-pink-700 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="bg-blue-400 p-3 rounded-full hover:bg-blue-500 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Navigasi</h4>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="text-gray-300 hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="profil.php" class="text-gray-300 hover:text-white transition-colors">Profil Sekolah</a></li>
                        <li><a href="academic.php" class="text-gray-300 hover:text-white transition-colors">Program Akademik</a></li>
                        <li><a href="inovasi.php" class="text-gray-300 hover:text-white transition-colors">Inovasi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                    <div class="space-y-3">
                        <p class="flex items-center text-gray-300">
                            <i class="fas fa-map-marker-alt mr-3 text-blue-400"></i>
                            Jl. Pendidikan No. 123, Jakarta
                        </p>
                        <p class="flex items-center text-gray-300">
                            <i class="fas fa-phone mr-3 text-blue-400"></i>
                            (021) 1234-5678
                        </p>
                        <p class="flex items-center text-gray-300">
                            <i class="fas fa-envelope mr-3 text-blue-400"></i>
                            info@sdcerdasceria.sch.id
                        </p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-12 pt-8 text-center">
                <p class="text-gray-400">&copy; <?php echo date('Y'); ?> SD Cerdas Ceria. All rights reserved. Made with ❤️</p>
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
    <script>
    // Contact form handling
    document.getElementById('contactForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        try {
            const response = await fetch('/sd/api/contact.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showSuccess();
                this.reset();
            } else {
                showError();
            }
        } catch (error) {
            console.error('Error submitting form:', error);
            showError();
        }
    });

    function showSuccess() {
        document.getElementById('success').classList.remove('hidden');
        document.getElementById('error').classList.add('hidden');
        setTimeout(() => {
            document.getElementById('success').classList.add('hidden');
        }, 5000);
    }

    function showError() {
        document.getElementById('error').classList.remove('hidden');
        document.getElementById('success').classList.add('hidden');
    }

    function openMaps() {
        // Replace with actual coordinates
        const address = encodeURIComponent('Jl. Pendidikan No. 123, Jakarta Pusat');
        window.open(`https://maps.google.com/maps?q=${address}`, '_blank');
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
