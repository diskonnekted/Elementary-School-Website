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
                        <a href="<?php echo htmlspecialchars($social_media['whatsapp']); ?>" target="_blank" rel="noopener">
                            <i class="fab fa-whatsapp"></i>
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
                        <?php if (!empty($contact_info['operating_hours'])): ?>
                        <li><i class="fas fa-clock"></i> <?php echo htmlspecialchars($contact_info['operating_hours']); ?></li>
                        <?php endif; ?>
                    </ul>
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
    </script>
</body>
</html>
