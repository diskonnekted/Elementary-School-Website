<?php
// Include settings if not already included
if (!class_exists('Settings')) {
    require_once __DIR__ . '/settings.php';
}

// Get school and contact info
$school_info = getSchoolInfo();
$contact_info = getContactInfo();
$social_media = getSocialMedia();
?>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <?php if (!empty($school_info['logo'])): ?>
                            <img src="admin/uploads/<?php echo htmlspecialchars($school_info['logo']); ?>" alt="Logo" style="height: 40px; width: auto; margin-right: 10px; background: white; padding: 2px; border-radius: 4px;">
                        <?php else: ?>
                            <i class="fas fa-graduation-cap"></i>
                        <?php endif; ?>
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
                        <li><a href="academic.php">Akademik</a></li>
                        <li><a href="inovasi.php">Inovasi</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Informasi</h3>
                    <ul>
                        <li><a href="info.php">Informasi Umum</a></li>
                        <li><a href="transparansi.php">Transparansi</a></li>
                        <li><a href="contact.php">Kontak</a></li>
                        <li><a href="index.php#integrity-values">Pendidikan Karakter</a></li>
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
                    <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($school_info['name']); ?>. All rights reserved.</p>
                    <p>NPSN: <?php echo htmlspecialchars($school_info['npsn']); ?></p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
        
        // Also support class-based hamburger (legacy)
        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('.nav-menu');
        
        if (hamburger && navMenu) {
            hamburger.addEventListener('click', function() {
                navMenu.classList.toggle('active');
                this.classList.toggle('active');
            });
        }
    </script>