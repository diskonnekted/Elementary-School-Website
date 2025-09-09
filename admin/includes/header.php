<?php
requireLogin();
$current_user = getCurrentUser();
$page_title = $page_title ?? 'Dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> - Admin SD Cerdas Ceria</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex flex-col w-64 bg-white border-r border-gray-200">
                <!-- Logo -->
                <div class="flex items-center px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-primary-500 to-purple-600 rounded-lg">
                            <i class="fas fa-graduation-cap text-white"></i>
                        </div>
                        <div class="ml-3">
                            <h2 class="text-lg font-semibold text-gray-900">SD Cerdas Ceria</h2>
                            <p class="text-sm text-gray-500">Admin Panel</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-4 overflow-y-auto sidebar-scroll">
                    <ul class="space-y-1">
                        <li>
                            <a href="index.php" class="flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-lg hover:bg-gray-100 <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : '' ?>">
                                <i class="fas fa-chart-pie mr-3 text-gray-400"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        <li>
                            <a href="news.php" class="flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-lg hover:bg-gray-100 <?= basename($_SERVER['PHP_SELF']) == 'news.php' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : '' ?>">
                                <i class="fas fa-newspaper mr-3 text-gray-400"></i>
                                Berita
                            </a>
                        </li>
                        
                        <li>
                            <a href="academic.php" class="flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-lg hover:bg-gray-100 <?= basename($_SERVER['PHP_SELF']) == 'academic.php' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : '' ?>">
                                <i class="fas fa-book-open mr-3 text-gray-400"></i>
                                Program Akademik
                            </a>
                        </li>
                        
                        <li>
                            <a href="info.php" class="flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-lg hover:bg-gray-100 <?= basename($_SERVER['PHP_SELF']) == 'info.php' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : '' ?>">
                                <i class="fas fa-info-circle mr-3 text-gray-400"></i>
                                Informasi Umum
                            </a>
                        </li>
                        
                        <li>
                            <a href="innovation.php" class="flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-lg hover:bg-gray-100 <?= basename($_SERVER['PHP_SELF']) == 'innovation.php' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : '' ?>">
                                <i class="fas fa-lightbulb mr-3 text-gray-400"></i>
                                Inovasi
                            </a>
                        </li>
                        
                        <li>
                            <a href="transparansi.php" class="flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-lg hover:bg-gray-100 <?= basename($_SERVER['PHP_SELF']) == 'transparansi.php' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : '' ?>">
                                <i class="fas fa-balance-scale mr-3 text-gray-400"></i>
                                Transparansi
                            </a>
                        </li>
                        
                        <li>
                            <a href="messages.php" class="flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-900 rounded-lg hover:bg-gray-100 <?= basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : '' ?>">
                                <div class="flex items-center">
                                    <i class="fas fa-envelope mr-3 text-gray-400"></i>
                                    Pesan Kontak
                                </div>
                                <?php 
                                try {
                                    require_once __DIR__ . '/../config/database.php';
                                    require_once __DIR__ . '/../models/ContactMessage.php';
                                    $db_check = new Database();
                                    $db_conn = $db_check->getConnection();
                                    $msg_check = new ContactMessage($db_conn);
                                    $stats_check = $msg_check->getStats();
                                    if ($stats_check['unread'] > 0) {
                                        echo '<span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 min-w-[1.25rem] text-center">' . $stats_check['unread'] . '</span>';
                                    }
                                } catch (Exception $e) {
                                    // Silently fail if there's an error
                                }
                                ?>
                            </a>
                        </li>
                        
                        <li>
                            <a href="media.php" class="flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-lg hover:bg-gray-100 <?= basename($_SERVER['PHP_SELF']) == 'media.php' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : '' ?>">
                                <i class="fas fa-images mr-3 text-gray-400"></i>
                                Galeri Media
                            </a>
                        </li>
                        
                        <li class="pt-4 mt-4 border-t border-gray-200">
                            <a href="settings.php" class="flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-lg hover:bg-gray-100 <?= basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : '' ?>">
                                <i class="fas fa-cog mr-3 text-gray-400"></i>
                                Pengaturan
                            </a>
                        </li>
                        
                        <li>
                            <a href="users.php" class="flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-lg hover:bg-gray-100 <?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : '' ?>">
                                <i class="fas fa-users mr-3 text-gray-400"></i>
                                Manajemen User
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- User Info -->
                <div class="px-4 py-3 border-t border-gray-200">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-8 h-8 bg-primary-100 rounded-full">
                            <i class="fas fa-user text-primary-600 text-sm"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($current_user['full_name']) ?></p>
                            <p class="text-xs text-gray-500 capitalize"><?= htmlspecialchars($current_user['role']) ?></p>
                        </div>
                        <a href="logout.php" class="text-gray-400 hover:text-gray-600" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button type="button" class="lg:hidden -ml-2 mr-2 p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100" onclick="toggleMobileSidebar()">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-900"><?= htmlspecialchars($page_title) ?></h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-2 text-gray-400 hover:text-gray-600 relative" onclick="toggleNotifications()">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-primary-500" onclick="toggleProfileMenu()">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-primary-600 text-sm"></i>
                                </div>
                                <span class="ml-2 text-gray-700 font-medium hidden sm:block"><?= htmlspecialchars($current_user['full_name']) ?></span>
                                <i class="fas fa-chevron-down ml-2 text-gray-400 text-xs"></i>
                            </button>

                            <!-- Profile Dropdown Menu -->
                            <div id="profileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profil Saya
                                </a>
                                <a href="settings.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Pengaturan
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Mobile Sidebar Overlay -->
            <div id="mobileSidebarOverlay" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 lg:hidden z-40"></div>

            <!-- Mobile Sidebar -->
            <div id="mobileSidebar" class="hidden fixed inset-y-0 left-0 z-50 w-64 bg-white lg:hidden transform -translate-x-full transition-transform duration-300">
                <!-- Mobile sidebar content (copy dari desktop sidebar) -->
                <div class="flex flex-col h-full">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-primary-500 to-purple-600 rounded-lg">
                                <i class="fas fa-graduation-cap text-white"></i>
                            </div>
                            <div class="ml-3">
                                <h2 class="text-lg font-semibold text-gray-900">SD Cerdas Ceria</h2>
                                <p class="text-sm text-gray-500">Admin Panel</p>
                            </div>
                        </div>
                        <button onclick="toggleMobileSidebar()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                    <!-- Navigation items sama seperti desktop -->
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto bg-gray-50 p-6">
                <?php
                // Display alerts
                $alert = getAlert();
                if ($alert):
                ?>
                <div class="mb-6">
                    <div class="rounded-md p-4 <?= $alert['type'] === 'success' ? 'bg-green-50 border border-green-200' : ($alert['type'] === 'error' ? 'bg-red-50 border border-red-200' : 'bg-blue-50 border border-blue-200') ?>">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas <?= $alert['type'] === 'success' ? 'fa-check-circle text-green-400' : ($alert['type'] === 'error' ? 'fa-exclamation-circle text-red-400' : 'fa-info-circle text-blue-400') ?>"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium <?= $alert['type'] === 'success' ? 'text-green-800' : ($alert['type'] === 'error' ? 'text-red-800' : 'text-blue-800') ?>">
                                    <?= htmlspecialchars($alert['message']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('mobileSidebarOverlay');
            
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                overlay.classList.remove('hidden');
                setTimeout(() => {
                    sidebar.classList.remove('-translate-x-full');
                }, 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                setTimeout(() => {
                    sidebar.classList.add('hidden');
                    overlay.classList.add('hidden');
                }, 300);
            }
        }

        function toggleProfileMenu() {
            const menu = document.getElementById('profileMenu');
            menu.classList.toggle('hidden');
        }

        function toggleNotifications() {
            // Implementasi untuk notifikasi
            console.log('Toggle notifications');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const profileMenu = document.getElementById('profileMenu');
            const profileButton = event.target.closest('[onclick="toggleProfileMenu()"]');
            
            if (!profileButton && !profileMenu.contains(event.target)) {
                profileMenu.classList.add('hidden');
            }
        });

        // Close mobile sidebar when clicking overlay
        document.getElementById('mobileSidebarOverlay')?.addEventListener('click', toggleMobileSidebar);
    </script>
