<!-- Create User Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="bg-blue-600 px-6 py-4 rounded-t-lg">
                <h3 class="text-lg font-semibold text-white">Tambah User Baru</h3>
            </div>
            <form method="POST" class="p-6">
                <input type="hidden" name="action" value="create">
                
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" id="username" name="username" required
                           pattern="[a-zA-Z0-9_]{3,50}"
                           title="Username harus 3-50 karakter (huruf, angka, underscore)"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="full_name" name="full_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required minlength="6"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                </div>
                
                <div class="mb-6">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select id="role" name="role" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="demo">Demo</option>
                        <option value="guru">Guru</option>
                        <option value="admin">Admin</option>
                        <?php if (Auth::getUserRole() === 'superadmin'): ?>
                        <option value="superadmin">Super Admin</option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateModal()" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="bg-green-600 px-6 py-4 rounded-t-lg">
                <h3 class="text-lg font-semibold text-white">Edit User</h3>
            </div>
            <form method="POST" class="p-6">
                <input type="hidden" name="action" value="update">
                <input type="hidden" id="edit_user_id" name="user_id">
                
                <div class="mb-4">
                    <label for="edit_username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" id="edit_username" name="username" required
                           pattern="[a-zA-Z0-9_]{3,50}"
                           title="Username harus 3-50 karakter (huruf, angka, underscore)"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="edit_email" name="email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="edit_full_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="edit_full_name" name="full_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select id="edit_role" name="role" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="demo">Demo</option>
                        <option value="guru">Guru</option>
                        <option value="admin">Admin</option>
                        <?php if (Auth::getUserRole() === 'superadmin'): ?>
                        <option value="superadmin">Super Admin</option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="edit_status" name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="bg-yellow-600 px-6 py-4 rounded-t-lg">
                <h3 class="text-lg font-semibold text-white">Ubah Password</h3>
            </div>
            <form method="POST" class="p-6">
                <input type="hidden" name="action" value="update_password">
                <input type="hidden" id="password_user_id" name="user_id">
                
                <div class="mb-4">
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                    <div class="relative">
                        <input type="password" id="new_password" name="new_password" required minlength="6"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10">
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePasswordVisibility('new_password', 'toggle_new_password')">
                            <i class="fas fa-eye text-gray-400" id="toggle_new_password"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                </div>
                
                <div class="mb-6">
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10">
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePasswordVisibility('confirm_password', 'toggle_confirm_password')">
                            <i class="fas fa-eye text-gray-400" id="toggle_confirm_password"></i>
                        </button>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closePasswordModal()" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">Ubah Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
function togglePasswordVisibility(inputId, iconId) {
    const passwordField = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Validate password confirmation
document.getElementById('passwordModal').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('Password konfirmasi tidak cocok!');
        return false;
    }
});

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'createModal') {
        closeCreateModal();
    } else if (e.target.id === 'editModal') {
        closeEditModal();
    } else if (e.target.id === 'passwordModal') {
        closePasswordModal();
    }
});
</script>
