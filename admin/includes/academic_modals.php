<!-- Create Program Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full">
            <div class="bg-primary-600 px-6 py-4 rounded-t-lg">
                <h3 class="text-lg font-semibold text-white">Tambah Program Baru</h3>
            </div>
            <form method="POST" class="p-6" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Judul Program *</label>
                        <input type="text" name="title" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi *</label>
                        <textarea name="description" rows="4" required
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tingkat Kelas *</label>
                            <select name="grade_level" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">Pilih Tingkat Kelas</option>
                                <?php foreach (['1', '2', '3', '4', '5', '6', 'semua'] as $grade): ?>
                                    <option value="<?php echo $grade; ?>">
                                        <?php echo $academic->getGradeLevelName($grade); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Kurikulum</label>
                            <select name="curriculum_type"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">Pilih Kurikulum</option>
                                <?php foreach (['nasional', 'internasional', 'muatan_lokal'] as $curriculum): ?>
                                    <option value="<?php echo $curriculum; ?>">
                                        <?php echo $academic->getCurriculumTypeName($curriculum); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                        <textarea name="subjects" rows="3" 
                                  placeholder='Contoh: Matematika, Bahasa Indonesia, IPA, IPS'
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                        <p class="mt-1 text-sm text-gray-500">Pisahkan dengan koma. Contoh: Matematika, Bahasa Indonesia</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Metode Pembelajaran</label>
                        <textarea name="learning_methods" rows="3" 
                                  placeholder='Contoh: Ceramah, Diskusi, Praktikum, Project Based Learning'
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                        <p class="mt-1 text-sm text-gray-500">Pisahkan dengan koma. Contoh: Ceramah, Diskusi</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Metode Penilaian</label>
                        <textarea name="assessment_methods" rows="3" 
                                  placeholder='Contoh: Ujian Tulis, Ujian Lisan, Tugas, Portofolio'
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                        <p class="mt-1 text-sm text-gray-500">Pisahkan dengan koma. Contoh: Ujian Tulis, Tugas</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gambar Program</label>
                        <input type="file" name="image" accept="image/*"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Urutan Tampilan</label>
                            <input type="number" name="sort_order" min="0" value="0"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        
                        <div class="flex items-center pt-6">
                            <input type="checkbox" name="is_active" value="1" checked
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-900">Program Aktif</label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeCreateModal()" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">Batal</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Program Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full">
            <div class="bg-yellow-500 px-6 py-4 rounded-t-lg">
                <h3 class="text-lg font-semibold text-white">Edit Program</h3>
            </div>
            <form method="POST" class="p-6" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" id="edit_id" name="id">
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Judul Program *</label>
                        <input type="text" id="edit_title" name="title" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi *</label>
                        <textarea id="edit_description" name="description" rows="4" required
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tingkat Kelas *</label>
                            <select id="edit_grade_level" name="grade_level" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">Pilih Tingkat Kelas</option>
                                <?php foreach (['1', '2', '3', '4', '5', '6', 'semua'] as $grade): ?>
                                    <option value="<?php echo $grade; ?>">
                                        <?php echo $academic->getGradeLevelName($grade); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Kurikulum</label>
                            <select id="edit_curriculum_type" name="curriculum_type"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">Pilih Kurikulum</option>
                                <?php foreach (['nasional', 'internasional', 'muatan_lokal'] as $curriculum): ?>
                                    <option value="<?php echo $curriculum; ?>">
                                        <?php echo $academic->getCurriculumTypeName($curriculum); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                        <textarea id="edit_subjects" name="subjects" rows="3" 
                                  placeholder='Contoh: Matematika, Bahasa Indonesia, IPA, IPS'
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                        <p class="mt-1 text-sm text-gray-500">Pisahkan dengan koma. Contoh: Matematika, Bahasa Indonesia</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Metode Pembelajaran</label>
                        <textarea id="edit_learning_methods" name="learning_methods" rows="3" 
                                  placeholder='Contoh: Ceramah, Diskusi, Praktikum, Project Based Learning'
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                        <p class="mt-1 text-sm text-gray-500">Pisahkan dengan koma. Contoh: Ceramah, Diskusi</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Metode Penilaian</label>
                        <textarea id="edit_assessment_methods" name="assessment_methods" rows="3" 
                                  placeholder='Contoh: Ujian Tulis, Ujian Lisan, Tugas, Portofolio'
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                        <p class="mt-1 text-sm text-gray-500">Pisahkan dengan koma. Contoh: Ujian Tulis, Tugas</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gambar Program</label>
                        <input type="file" name="image" accept="image/*"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <div id="edit_image_preview" class="mt-2 hidden">
                            <img src="" alt="Current image" class="h-32 w-auto rounded">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Urutan Tampilan</label>
                            <input type="number" id="edit_sort_order" name="sort_order" min="0"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        
                        <div class="flex items-center pt-6">
                            <input type="checkbox" id="edit_is_active" name="is_active" value="1" 
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-900">Program Aktif</label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">Batal</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openEditModal(id) {
    // Fetch data via AJAX
    fetch(`academic.php?action=get_academic&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.getElementById('edit_id').value = data.id;
                document.getElementById('edit_title').value = data.title;
                document.getElementById('edit_description').value = data.description;
                document.getElementById('edit_grade_level').value = data.grade_level;
                document.getElementById('edit_curriculum_type').value = data.curriculum_type;
                document.getElementById('edit_subjects').value = data.subjects;
                document.getElementById('edit_learning_methods').value = data.learning_methods;
                document.getElementById('edit_assessment_methods').value = data.assessment_methods;
                document.getElementById('edit_sort_order').value = data.sort_order;
                document.getElementById('edit_is_active').checked = data.is_active == 1;
                
                // Image preview
                const preview = document.getElementById('edit_image_preview');
                const img = preview.querySelector('img');
                if (data.image) {
                    img.src = data.image;
                    preview.classList.remove('hidden');
                } else {
                    preview.classList.add('hidden');
                }
                
                document.getElementById('editModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        })
        .catch(error => {
            console.error('Error fetching academic program:', error);
            alert('Gagal mengambil data program.');
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modals when clicking outside
window.onclick = function(event) {
    const createModal = document.getElementById('createModal');
    const editModal = document.getElementById('editModal');
    if (event.target === createModal) {
        closeCreateModal();
    }
    if (event.target === editModal) {
        closeEditModal();
    }
}
</script>
