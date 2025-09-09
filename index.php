<?php
require_once 'includes/settings.php';

$page_title = 'Beranda';
$school_info = getSchoolInfo();
$contact_info = getContactInfo();
$social_media = getSocialMedia();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($school_info['name']); ?> - Sekolah Dasar Modern</title>
    <?php include 'includes/favicon.php'; ?>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                        'jakarta': ['Plus Jakarta Sans', 'sans-serif']
                    },
                    colors: {
                        'primary': {
                            50: '#eef2ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81'
                        },
                        'accent': {
                            500: '#06d6a0',
                            600: '#05c195'
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'fade-in-up': 'fade-in-up 0.6s ease-out',
                        'slide-in-right': 'slide-in-right 0.8s ease-out',
                        'pulse-glow': 'pulse-glow 2s ease-in-out infinite alternate'
                    },
                    backgroundImage: {
                        'hero-gradient': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                        'card-gradient': 'linear-gradient(145deg, #f8fafc 0%, #e2e8f0 100%)'
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes fade-in-up {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes slide-in-right {
            0% { opacity: 0; transform: translateX(30px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        @keyframes pulse-glow {
            0% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.4); }
            100% { box-shadow: 0 0 40px rgba(99, 102, 241, 0.8); }
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .text-shadow { text-shadow: 2px 2px 4px rgba(0,0,0,0.1); }
        .hover-lift:hover { transform: translateY(-5px); transition: all 0.3s ease; }
    </style>
</head>
<body class="font-inter bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <!-- Modern Navigation -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300" id="navbar">
        <div class="glass-effect">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3 animate-fade-in-up">
                        <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center shadow-lg animate-pulse-glow">
                                <i class="fas fa-graduation-cap text-white text-xl"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-accent-500 rounded-full animate-ping"></div>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">
                                <?php echo htmlspecialchars($school_info['name']); ?>
                            </h1>
                            <p class="text-xs text-gray-500 font-medium">Modern Education</p>
                        </div>
                    </div>
                    
                    <!-- Desktop Menu -->
                    <div class="hidden lg:flex items-center space-x-1">
                        <a href="index.php" class="px-4 py-2 rounded-full text-sm font-semibold bg-primary-500 text-white shadow-lg hover:bg-primary-600 transition-all duration-300 hover-lift">
                            Beranda
                        </a>
                        <a href="profil.php" class="px-4 py-2 rounded-full text-sm font-medium text-gray-700 hover:bg-white/50 hover:text-primary-600 transition-all duration-300">
                            Profil
                        </a>
                        <a href="berita.php" class="px-4 py-2 rounded-full text-sm font-medium text-gray-700 hover:bg-white/50 hover:text-primary-600 transition-all duration-300">
                            Berita
                        </a>
                        <a href="academic.php" class="px-4 py-2 rounded-full text-sm font-medium text-gray-700 hover:bg-white/50 hover:text-primary-600 transition-all duration-300">
                            Akademik
                        </a>
                        <div class="relative group">
                            <a href="info.php" class="px-4 py-2 rounded-full text-sm font-medium text-gray-700 hover:bg-white/50 hover:text-primary-600 transition-all duration-300 flex items-center">
                                Info <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </a>
                            <div class="absolute top-full left-0 mt-2 w-48 bg-white/90 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                                <a href="info.php" class="block px-4 py-3 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-t-2xl transition-all duration-300">
                                    <i class="fas fa-info-circle mr-2"></i>Informasi Umum
                                </a>
                                <a href="transparansi.php" class="block px-4 py-3 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-b-2xl transition-all duration-300">
                                    <i class="fas fa-balance-scale mr-2"></i>Transparansi
                                </a>
                            </div>
                        </div>
                        <a href="inovasi.php" class="px-4 py-2 rounded-full text-sm font-medium text-gray-700 hover:bg-white/50 hover:text-primary-600 transition-all duration-300">
                            Inovasi
                        </a>
                        <a href="contact.php" class="px-4 py-2 rounded-full text-sm font-medium text-gray-700 hover:bg-white/50 hover:text-primary-600 transition-all duration-300">
                            Kontak
                        </a>
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <button class="lg:hidden p-2 rounded-full hover:bg-white/20 transition-all duration-300" id="mobile-menu-button">
                        <i class="fas fa-bars text-primary-600 text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div class="lg:hidden bg-white/95 backdrop-blur-md border-t border-white/20 hidden" id="mobile-menu">
                <div class="px-4 py-6 space-y-3">
                    <a href="index.php" class="block px-4 py-3 rounded-2xl text-sm font-semibold bg-primary-500 text-white text-center">
                        Beranda
                    </a>
                    <a href="profil.php" class="block px-4 py-3 rounded-2xl text-sm font-medium text-gray-700 hover:bg-primary-50 transition-all duration-300">
                        Profil
                    </a>
                    <a href="berita.php" class="block px-4 py-3 rounded-2xl text-sm font-medium text-gray-700 hover:bg-primary-50 transition-all duration-300">
                        Berita
                    </a>
                    <a href="academic.php" class="block px-4 py-3 rounded-2xl text-sm font-medium text-gray-700 hover:bg-primary-50 transition-all duration-300">
                        Akademik
                    </a>
                    <a href="info.php" class="block px-4 py-3 rounded-2xl text-sm font-medium text-gray-700 hover:bg-primary-50 transition-all duration-300">
                        Informasi Umum
                    </a>
                    <a href="transparansi.php" class="block px-4 py-3 rounded-2xl text-sm font-medium text-gray-700 hover:bg-primary-50 transition-all duration-300">
                        Transparansi
                    </a>
                    <a href="inovasi.php" class="block px-4 py-3 rounded-2xl text-sm font-medium text-gray-700 hover:bg-primary-50 transition-all duration-300">
                        Inovasi
                    </a>
                    <a href="contact.php" class="block px-4 py-3 rounded-2xl text-sm font-medium text-gray-700 hover:bg-primary-50 transition-all duration-300">
                        Kontak
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Modern Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900">
            <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-accent-500/10 to-accent-500/20"></div>
        </div>
        
        <!-- Floating Geometric Shapes -->
        <div class="absolute inset-0">
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-gradient-to-br from-accent-500/20 to-primary-300/20 rounded-full blur-3xl animate-float"></div>
            <div class="absolute top-3/4 right-1/4 w-96 h-96 bg-gradient-to-br from-primary-300/20 to-accent-500/20 rounded-full blur-3xl animate-float" style="animation-delay: -2s;"></div>
            <div class="absolute top-1/2 left-1/2 w-32 h-32 bg-white/5 rounded-2xl rotate-45 animate-float" style="animation-delay: -4s;"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left animate-fade-in-up">
                    <!-- Badge -->
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white/90 text-sm font-medium mb-6">
                        <i class="fas fa-star mr-2 text-accent-400"></i>
                        Sekolah Berintegritas Terdepan
                    </div>
                    
                    <!-- Main Headline -->
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white leading-tight mb-6">
                        Membentuk
                        <span class="bg-gradient-to-r from-accent-400 to-yellow-300 bg-clip-text text-transparent animate-pulse-glow">
                            Generasi
                        </span>
                        <br>Berintegritas
                    </h1>
                    
                    <!-- Subtitle -->
                    <p class="text-xl md:text-2xl text-white/80 leading-relaxed mb-8 max-w-2xl">
                        <?php echo htmlspecialchars($school_info['name']); ?> menghadirkan pendidikan karakter yang holistik dengan menanamkan 9 nilai anti-korupsi sejak dini.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="profil.html" class="group inline-flex items-center justify-center px-8 py-4 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-2xl shadow-2xl hover-lift transition-all duration-300">
                            <i class="fas fa-school mr-3 group-hover:scale-110 transition-transform"></i>
                            Tentang Kami
                            <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="akademik.php" class="group inline-flex items-center justify-center px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white font-semibold rounded-2xl hover-lift transition-all duration-300">
                            <i class="fas fa-book-open mr-3 group-hover:scale-110 transition-transform"></i>
                            Program Akademik
                        </a>
                    </div>
                    
                    <!-- Stats Row -->
                    <div class="grid grid-cols-3 gap-4 mt-12 pt-8 border-t border-white/20">
                        <div class="text-center">
                            <div class="text-2xl md:text-3xl font-bold text-white mb-1">500+</div>
                            <div class="text-sm text-white/70">Siswa Aktif</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl md:text-3xl font-bold text-white mb-1"><?php echo htmlspecialchars($school_info['accreditation']); ?></div>
                            <div class="text-sm text-white/70">Akreditasi</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl md:text-3xl font-bold text-white mb-1">25+</div>
                            <div class="text-sm text-white/70">Guru Expert</div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Content - 3D Card -->
                <div class="relative animate-slide-in-right">
                    <div class="relative">
                        <!-- Main Card -->
                        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 hover-lift transition-all duration-500 hover:bg-white/15">
                            <div class="flex items-center space-x-4 mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-accent-400 to-accent-600 rounded-2xl flex items-center justify-center animate-pulse-glow">
                                    <i class="fas fa-shield-heart text-white text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white">Pendidikan Karakter</h3>
                                    <p class="text-white/70">Anti-Korupsi Sejak Dini</p>
                                </div>
                            </div>
                            
                            <p class="text-white/80 leading-relaxed mb-6">
                                Menanamkan 9 nilai integritas: Kejujuran, Tanggung Jawab, Disiplin, Keadilan, Kepedulian, Kesederhanaan, Kerja Keras, Kemandirian, dan Keberanian.
                            </p>
                            
                            <!-- Values Grid -->
                            <div class="grid grid-cols-3 gap-3">
                                <div class="bg-white/10 rounded-xl p-3 text-center hover:bg-white/20 transition-all duration-300">
                                    <div class="text-2xl mb-1">🤝</div>
                                    <div class="text-xs text-white/80">Kejujuran</div>
                                </div>
                                <div class="bg-white/10 rounded-xl p-3 text-center hover:bg-white/20 transition-all duration-300">
                                    <div class="text-2xl mb-1">📚</div>
                                    <div class="text-xs text-white/80">Tanggung Jawab</div>
                                </div>
                                <div class="bg-white/10 rounded-xl p-3 text-center hover:bg-white/20 transition-all duration-300">
                                    <div class="text-2xl mb-1">⏰</div>
                                    <div class="text-xs text-white/80">Disiplin</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Floating Mini Cards -->
                        <div class="absolute -top-4 -right-4 w-20 h-20 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center animate-float shadow-2xl">
                            <i class="fas fa-star text-white text-xl"></i>
                        </div>
                        
                        <div class="absolute -bottom-6 -left-6 w-16 h-16 bg-gradient-to-br from-green-400 to-blue-500 rounded-2xl flex items-center justify-center animate-float shadow-2xl" style="animation-delay: -3s;">
                            <i class="fas fa-heart text-white"></i>
                        </div>
                        
                        <div class="absolute top-1/2 -right-8 w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full flex items-center justify-center animate-float shadow-2xl" style="animation-delay: -1s;">
                            <i class="fas fa-lightbulb text-white text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <div class="w-8 h-12 border-2 border-white/30 rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white/60 rounded-full mt-2 animate-pulse"></div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Keunggulan <?php echo htmlspecialchars($school_info['name']); ?></h2>
                <p class="section-subtitle">Membangun karakter berintegritas melalui pendidikan yang holistik dan inovatif</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Pendidikan Kejujuran</h3>
                    <p>Menanamkan nilai kejujuran melalui kantin kejujuran dan budaya berkata benar</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h3>Keadilan & Kepedulian</h3>
                    <p>Menciptakan lingkungan yang adil dan peduli antar sesama siswa dan guru</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3>Tanggung Jawab</h3>
                    <p>Membentuk siswa yang bertanggung jawab terhadap tugas dan lingkungan sekolah</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>Prestasi Berintegritas</h3>
                    <p>Meraih prestasi dengan cara yang jujur dan kerja keras tanpa kecurangan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 9 Nilai Integritas Section -->
    <section class="integrity-values" style="background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="section-header" style="text-align: center; margin-bottom: 60px;">
                <h2 class="section-title" style="color: white; font-size: 2.5rem; margin-bottom: 16px;">
                    9 Nilai Integritas <?php echo htmlspecialchars($school_info['name']); ?>
                </h2>
                <p class="section-subtitle" style="color: rgba(255,255,255,0.9); font-size: 1.2rem;">
                    Membangun karakter anti-korupsi sejak dini untuk generasi Indonesia yang berintegritas
                </p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 40px;">
                <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 24px; border-radius: 16px; text-align: center; border: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 3rem; margin-bottom: 16px;">🤝</div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 12px; color: #FEF3C7;">Kejujuran</h3>
                    <p style="color: rgba(255,255,255,0.8); line-height: 1.6;">Berkata benar, tidak menyontek, dan mengakui kesalahan dengan lapang dada</p>
                </div>
                
                <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 24px; border-radius: 16px; text-align: center; border: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 3rem; margin-bottom: 16px;">📚</div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 12px; color: #FEF3C7;">Tanggung Jawab</h3>
                    <p style="color: rgba(255,255,255,0.8); line-height: 1.6;">Mengerjakan tugas dengan sungguh-sungguh dan bertanggung jawab atas perbuatan</p>
                </div>
                
                <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 24px; border-radius: 16px; text-align: center; border: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 3rem; margin-bottom: 16px;">⏰</div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 12px; color: #FEF3C7;">Disiplin</h3>
                    <p style="color: rgba(255,255,255,0.8); line-height: 1.6;">Tepat waktu, tertib, dan mengikuti tata tertib sekolah dengan baik</p>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 40px;">
                <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 24px; border-radius: 16px; text-align: center; border: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 3rem; margin-bottom: 16px;">⚖️</div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 12px; color: #FEF3C7;">Keadilan</h3>
                    <p style="color: rgba(255,255,255,0.8); line-height: 1.6;">Tidak pilih kasih, berbagi dengan teman, dan menghormati perbedaan</p>
                </div>
                
                <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 24px; border-radius: 16px; text-align: center; border: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 3rem; margin-bottom: 16px;">💝</div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 12px; color: #FEF3C7;">Kepedulian</h3>
                    <p style="color: rgba(255,255,255,0.8); line-height: 1.6;">Membantu teman yang kesulitan dan menjaga kebersihan lingkungan</p>
                </div>
                
                <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 24px; border-radius: 16px; text-align: center; border: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 3rem; margin-bottom: 16px;">🌱</div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 12px; color: #FEF3C7;">Kesederhanaan</h3>
                    <p style="color: rgba(255,255,255,0.8); line-height: 1.6;">Hidup hemat, tidak sombong, dan bersyukur dengan yang dimiliki</p>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
                <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 24px; border-radius: 16px; text-align: center; border: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 3rem; margin-bottom: 16px;">🏃</div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 12px; color: #FEF3C7;">Kerja Keras</h3>
                    <p style="color: rgba(255,255,255,0.8); line-height: 1.6;">Berusaha maksimal, pantang menyerah, dan tidak mudah putus asa</p>
                </div>
                
                <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 24px; border-radius: 16px; text-align: center; border: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 3rem; margin-bottom: 16px;">🎯</div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 12px; color: #FEF3C7;">Kemandirian</h3>
                    <p style="color: rgba(255,255,255,0.8); line-height: 1.6;">Tidak bergantung pada orang lain, berani mengambil keputusan sendiri</p>
                </div>
                
                <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 24px; border-radius: 16px; text-align: center; border: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 3rem; margin-bottom: 16px;">🙏</div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 12px; color: #FEF3C7;">Berani</h3>
                    <p style="color: rgba(255,255,255,0.8); line-height: 1.6;">Berani mengatakan yang benar, melaporkan pelanggaran, dan membela keadilan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Principal Message Section -->
    <section class="principal-message" style="padding: 80px 0; background: #f8fafc;">
        <div class="container">
            <div class="section-header" style="text-align: center; margin-bottom: 60px;">
                <h2 class="section-title">Pesan Kepala Sekolah</h2>
                <p class="section-subtitle">Komitmen kami untuk pendidikan berintegritas</p>
            </div>
            
            <div style="max-width: 800px; margin: 0 auto; text-align: center;">
                <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <div style="width: 100px; height: 100px; border-radius: 50%; margin: 0 auto 24px; background: linear-gradient(135deg, #3B82F6, #1E40AF); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-tie" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; color: #1E40AF; margin-bottom: 12px;">
                        <?php echo htmlspecialchars($school_info['principal_name']); ?>
                    </h3>
                    <p style="color: #6B7280; margin-bottom: 24px; font-weight: 500;">Kepala Sekolah</p>
                    <blockquote style="font-style: italic; font-size: 1.1rem; line-height: 1.8; color: #374151; margin-bottom: 24px;">
                        "Selamat datang di <?php echo htmlspecialchars($school_info['name']); ?>. Kami berkomitmen untuk membentuk generasi yang tidak hanya cerdas secara akademik, tetapi juga memiliki karakter yang kuat dan integritas yang tinggi. Melalui pendidikan anti-korupsi sejak dini, kami mempersiapkan anak-anak untuk menjadi pemimpin masa depan yang jujur dan bertanggung jawab."
                    </blockquote>
                    <p style="font-weight: 600; color: #1E40AF;">
                        <?php echo htmlspecialchars($school_info['motto']); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats" style="background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; text-align: center;">
                <div>
                    <div style="font-size: 3rem; font-weight: 700; margin-bottom: 12px;"><?php echo htmlspecialchars($school_info['established_year']); ?></div>
                    <p style="color: rgba(255,255,255,0.9); font-size: 1.1rem;">Tahun Berdiri</p>
                </div>
                <div>
                    <div style="font-size: 3rem; font-weight: 700; margin-bottom: 12px;">500+</div>
                    <p style="color: rgba(255,255,255,0.9); font-size: 1.1rem;">Siswa Aktif</p>
                </div>
                <div>
                    <div style="font-size: 3rem; font-weight: 700; margin-bottom: 12px;"><?php echo htmlspecialchars($school_info['accreditation']); ?></div>
                    <p style="color: rgba(255,255,255,0.9); font-size: 1.1rem;">Akreditasi</p>
                </div>
                <div>
                    <div style="font-size: 3rem; font-weight: 700; margin-bottom: 12px;">25+</div>
                    <p style="color: rgba(255,255,255,0.9); font-size: 1.1rem;">Guru Berpengalaman</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" style="padding: 80px 0; background: #f8fafc;">
        <div class="container">
            <div style="max-width: 600px; margin: 0 auto; text-align: center;">
                <h2 style="font-size: 2.5rem; color: #1E40AF; margin-bottom: 24px; font-weight: 700;">
                    Bergabunglah dengan Kami
                </h2>
                <p style="font-size: 1.2rem; color: #6B7280; margin-bottom: 32px; line-height: 1.6;">
                    Mari bersama-sama membangun generasi berintegritas untuk masa depan Indonesia yang lebih baik
                </p>
                <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                    <a href="profil.php" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-info-circle"></i>
                        Pelajari Lebih Lanjut
                    </a>
                    <a href="kontak.html" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-phone"></i>
                        Hubungi Kami
                    </a>
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
                        <a href="<?php echo htmlspecialchars($social_media['facebook']); ?>" target="_blank" rel="noopener">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($social_media['instagram']); ?>" target="_blank" rel="noopener">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($social_media['youtube']); ?>" target="_blank" rel="noopener">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($social_media['twitter']); ?>" target="_blank" rel="noopener">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Menu Utama</h3>
                    <ul>
                        <li><a href="profil.php">Profil</a></li>
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
            
            <div class="footer-bottom">
                <div class="footer-divider"></div>
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                    <p>&copy; 2024 <?php echo htmlspecialchars($school_info['name']); ?>. All rights reserved.</p>
                    <p>NPSN: <?php echo htmlspecialchars($school_info['npsn']); ?></p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.querySelector('.hamburger').addEventListener('click', function() {
            document.querySelector('.nav-menu').classList.toggle('active');
            this.classList.toggle('active');
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
