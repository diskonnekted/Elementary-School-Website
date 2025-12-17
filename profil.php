<?php
// Include necessary files
include_once 'includes/settings.php';

// Get school info
$school_info = getSchoolInfo();
$contact_info = getContactInfo();

// Set page title
$page_title = "Profil - " . $school_info['name'];
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
                        <a href="profil.php" class="nav-link active">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a href="berita.php" class="nav-link">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a href="academic.php" class="nav-link">Akademik</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="info.php" class="nav-link dropdown-toggle">Info</a>
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
    <section class="page-header canvas-overlay canvas-dots">
        <div class="container">
            <div class="page-header-content">
                <h1 class="canvas-text-paint canvas-text-shadow">Profil Sekolah</h1>
                <p>Mengenal lebih dekat <?php echo htmlspecialchars($school_info['name']); ?></p>
                <nav class="breadcrumb">
                    <a href="index.php">Beranda</a>
                    <span>/</span>
                    <span>Profil</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about canvas-overlay">
        <div class="container">
            <div class="about-grid">
                <div class="about-image">
                    <div class="school-image canvas-card">
                        <img src="images/sch3.jpg" alt="Gedung <?php echo htmlspecialchars($school_info['name']); ?>" class="school-building">
                        <div class="image-overlay">
                            <h4>Gedung <?php echo htmlspecialchars($school_info['name']); ?></h4>
                            <p>Fasilitas Modern & Nyaman</p>
                        </div>
                    </div>
                </div>
                <div class="about-content">
                    <h2 class="canvas-text-paint">Tentang <?php echo htmlspecialchars($school_info['name']); ?></h2>
                    <p><?php echo !empty($school_info['description']) ? htmlspecialchars($school_info['description']) : htmlspecialchars($school_info['name']) . ' didirikan pada tahun ' . ($school_info['established_year'] ?: '2009') . ' dengan visi menjadi sekolah dasar terdepan dalam menghasilkan generasi yang cerdas, berkarakter, dan berdaya saing global. Kami berkomitmen memberikan pendidikan berkualitas tinggi dengan menggabungkan kurikulum nasional dan internasional.'; ?></p>
                    
                    <p>Dengan fasilitas modern dan tenaga pengajar profesional, kami menciptakan lingkungan belajar yang kondusif untuk mengembangkan potensi setiap siswa secara optimal, baik dari segi akademik, karakter, maupun keterampilan life skills.</p>
                    
                    <div class="achievement-stats">
                        <div class="stat">
                            <span class="number">15+</span>
                            <span class="label">Tahun Pengalaman</span>
                        </div>
                        <div class="stat">
                            <span class="number">500+</span>
                            <span class="label">Alumni Sukses</span>
                        </div>
                        <div class="stat">
                            <span class="number">98%</span>
                            <span class="label">Tingkat Kelulusan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision Mission Section -->
    <section class="vision-mission canvas-overlay canvas-grid">
        <div class="container">
            <div class="vm-grid">
                <div class="vm-card canvas-card">
                    <div class="vm-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="canvas-text-paint">Visi</h3>
                    <p>Menjadi sekolah dasar unggulan yang menghasilkan generasi cerdas, berkarakter mulia, dan berwawasan global untuk membangun masa depan bangsa yang gemilang.</p>
                </div>
                <div class="vm-card canvas-card">
                    <div class="vm-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3 class="canvas-text-paint">Misi</h3>
                    <ul>
                        <li>Menyelenggarakan pendidikan berkualitas dengan standar nasional dan internasional</li>
                        <li>Mengembangkan karakter siswa berdasarkan nilai-nilai Pancasila</li>
                        <li>Menerapkan teknologi pembelajaran terkini</li>
                        <li>Menciptakan lingkungan belajar yang aman, nyaman, dan inspiratif</li>
                        <li>Membangun kemitraan dengan orang tua dan masyarakat</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values canvas-overlay">
        <div class="container">
            <div class="section-header">
                <h2 class="canvas-text-paint canvas-text-shadow">Nilai-Nilai Sekolah</h2>
                <p>Fondasi karakter yang kami tanamkan kepada setiap siswa</p>
            </div>
            
            <div class="values-grid">
                <div class="value-card canvas-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4 class="canvas-text-paint">CERDAS</h4>
                    <p>Cinta belajar, Efektif, Religius, Disiplin, Aktif, Santun</p>
                </div>
                
                <div class="value-card canvas-card">
                    <div class="value-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h4 class="canvas-text-paint">CERIA</h4>
                    <p>Cerdas, Empati, Responsif, Inovatif, Adaptif</p>
                </div>
                
                <div class="value-card canvas-card">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h4 class="canvas-text-paint">INTEGRITAS</h4>
                    <p>Jujur, bertanggung jawab, dan konsisten dalam perkataan dan perbuatan</p>
                </div>
                
                <div class="value-card canvas-card">
                    <div class="value-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h4 class="canvas-text-paint">GLOBAL</h4>
                    <p>Berpikiran terbuka dan siap menghadapi tantangan global</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Facilities Section -->
    <section class="facilities canvas-overlay canvas-diagonal">
        <div class="container">
            <div class="section-header">
                <h2 class="canvas-text-paint canvas-text-shadow">Fasilitas Unggulan</h2>
                <p>Sarana dan prasarana terbaik untuk mendukung proses pembelajaran</p>
            </div>
            
            <div class="facilities-grid">
                <div class="facility-card canvas-card">
                    <div class="facility-image-container">
                        <img src="images/sd1.jpg" alt="Smart Classroom" class="facility-image">
                    </div>
                    <div class="facility-content">
                        <h4 class="canvas-text-paint">Smart Classroom</h4>
                        <p>Ruang kelas dilengkapi dengan teknologi digital interaktif</p>
                    </div>
                </div>
                
                <div class="facility-card canvas-card">
                    <div class="facility-image-container">
                        <img src="images/sd2.jpg" alt="Perpustakaan Modern" class="facility-image">
                    </div>
                    <div class="facility-content">
                        <h4 class="canvas-text-paint">Perpustakaan Modern</h4>
                        <p>Koleksi buku lengkap dengan sistem digital dan ruang baca nyaman</p>
                    </div>
                </div>
                
                <div class="facility-card canvas-card">
                    <div class="facility-image-container">
                        <img src="images/sd3.jpg" alt="Laboratorium Sains" class="facility-image">
                    </div>
                    <div class="facility-content">
                        <h4 class="canvas-text-paint">Laboratorium Sains</h4>
                        <p>Laboratorium lengkap untuk eksplorasi dan eksperimen sains</p>
                    </div>
                </div>
                
                <div class="facility-card canvas-card">
                    <div class="facility-image-container">
                        <img src="images/sd4.jpg" alt="Lapangan Olahraga" class="facility-image">
                    </div>
                    <div class="facility-content">
                        <h4 class="canvas-text-paint">Lapangan Olahraga</h4>
                        <p>Area olahraga yang luas untuk berbagai aktivitas fisik</p>
                    </div>
                </div>
                
                <div class="facility-card canvas-card">
                    <div class="facility-image-container">
                        <img src="images/sd5.jpg" alt="Ruang Seni" class="facility-image">
                    </div>
                    <div class="facility-content">
                        <h4 class="canvas-text-paint">Ruang Seni</h4>
                        <p>Studio seni untuk mengembangkan kreativitas dan bakat siswa</p>
                    </div>
                </div>
                
                <div class="facility-card canvas-card">
                    <div class="facility-image-container">
                        <img src="images/sd6.jpg" alt="Ruang Musik" class="facility-image">
                    </div>
                    <div class="facility-content">
                        <h4 class="canvas-text-paint">Ruang Musik</h4>
                        <p>Studio musik dengan berbagai alat musik untuk pembelajaran</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="js/script.js"></script>
</body>
</html>
