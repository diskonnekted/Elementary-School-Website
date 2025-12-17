<?php
// Include settings if not already included
if (!class_exists('Settings')) {
    require_once __DIR__ . '/settings.php';
}

// Get school info
$school_info = getSchoolInfo();
$page_title = $page_title ?? 'SD Cerdas Ceria';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title . ' - ' . $school_info['name']); ?></title>
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
                    <?php if (!empty($school_info['logo'])): ?>
                        <img src="admin/uploads/<?php echo htmlspecialchars($school_info['logo']); ?>" alt="Logo" style="height: 40px; width: auto; margin-right: 10px;">
                    <?php else: ?>
                        <i class="fas fa-graduation-cap"></i>
                    <?php endif; ?>
                    <span><?php echo htmlspecialchars($school_info['name']); ?></span>
                </div>
                
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="index.html" class="nav-link">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a href="profil.php" class="nav-link">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a href="berita.php" class="nav-link">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a href="akademik.php" class="nav-link">Akademik</a>
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
                        <a href="kontak.html" class="nav-link">Kontak</a>
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
