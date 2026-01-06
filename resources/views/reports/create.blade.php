<x-layouts.app title="Buat Laporan">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Buat Laporan Baru</h1>
        <p class="text-gray-600 mt-1">Pilih template atau isi formulir dari awal</p>
    </div>

    <!-- Template Selector -->
    @if(isset($templates) && $templates->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">📋 Pilih Template</h2>
                <p class="text-sm text-gray-500">Gunakan template untuk mempercepat pengisian laporan</p>
            </div>
            <button type="button" onclick="toggleTemplates()" id="toggleBtn" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                Sembunyikan
            </button>
        </div>
        
        <div id="templateGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @php
                $groupedTemplates = $templates->groupBy('category');
                $categoryLabels = [
                    'perilaku' => '⚠️ Perilaku',
                    'akademik' => '📚 Akademik',
                    'sosial' => '👥 Sosial',
                    'fasilitas' => '🏫 Fasilitas',
                    'keamanan' => '🔒 Keamanan',
                    'lainnya' => '📝 Lainnya',
                ];
            @endphp
            @foreach($groupedTemplates as $category => $categoryTemplates)
                <div class="space-y-2">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $categoryLabels[$category] ?? ucfirst($category) }}</h4>
                    @foreach($categoryTemplates as $template)
                    <button type="button" 
                        onclick="applyTemplate({{ $template->id }}, '{{ addslashes($template->title_template) }}', `{{ addslashes($template->content_template) }}`)"
                        class="w-full text-left p-3 bg-gray-50 hover:bg-primary-50 border border-gray-200 hover:border-primary-300 rounded-lg transition-all group">
                        <div class="flex items-center">
                            <span class="text-lg mr-2">{{ $template->category_icon }}</span>
                            <span class="font-medium text-gray-900 group-hover:text-primary-700 text-sm">{{ $template->name }}</span>
                        </div>
                    </button>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Laporan <span class="text-danger-500">*</span>
                </label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" 
                    maxlength="100" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('title') border-danger-500 @enderror"
                    placeholder="Masukkan judul laporan (maks 100 karakter)">
                <p class="mt-1 text-sm text-gray-500">Maksimal 100 karakter</p>
                @error('title')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
            </div>

            <!-- Accused Persons (Multi-select with tags) -->
            @if(isset($reportableUsers) && $reportableUsers->count() > 0)
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Orang yang Dilaporkan <span class="text-gray-400 font-normal">(Opsional, bisa lebih dari satu)</span>
                </label>
                
                <!-- Tag-style input container -->
                <div id="accusedContainer" class="w-full min-h-[48px] px-3 py-2 border border-gray-200 rounded-xl focus-within:ring-2 focus-within:ring-primary-500 focus-within:border-transparent bg-white">
                    <!-- Selected tags will appear here -->
                    <div id="selectedTags" class="flex flex-wrap gap-2 mb-2"></div>
                    
                    <!-- Search input -->
                    <input type="text" id="accusedSearch" 
                        placeholder="Ketik nama untuk mencari..."
                        class="w-full border-0 focus:ring-0 p-0 text-sm placeholder-gray-400"
                        autocomplete="off">
                    
                    <!-- Hidden inputs for form submission -->
                    <div id="hiddenInputs"></div>
                </div>
                
                <!-- Dropdown suggestions -->
                <div id="accusedDropdown" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                    @php
                        $groupedUsers = $reportableUsers->groupBy(function($user) {
                            return match($user->role) {
                                'siswa' => 'Siswa',
                                'guru' => 'Guru',
                                'staf_kesiswaan' => 'Staf Kesiswaan',
                                default => 'Lainnya'
                            };
                        });
                    @endphp
                    @foreach($groupedUsers as $roleLabel => $users)
                        <div class="px-3 py-2 bg-gray-50 text-xs font-semibold text-gray-500 uppercase border-b">{{ $roleLabel }}</div>
                        @foreach($users as $person)
                            <div class="accused-option px-4 py-2 hover:bg-primary-50 cursor-pointer flex items-center justify-between"
                                 data-id="{{ $person->id }}" 
                                 data-name="{{ $person->name }}"
                                 data-role="{{ $roleLabel }}">
                                <span>{{ $person->name }}</span>
                                <span class="text-xs text-gray-400">{{ $roleLabel }}</span>
                            </div>
                        @endforeach
                    @endforeach
                </div>
                
                <p class="mt-1 text-sm text-gray-500">Kosongkan jika laporan bersifat umum (misal: kerusakan fasilitas). Laporan umum tidak mempengaruhi indeks siapapun.</p>
                @error('accused_user_ids')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
            </div>
            @endif

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Isi Laporan <span class="text-danger-500">*</span>
                </label>
                <textarea id="content" name="content" rows="6" 
                    minlength="20" maxlength="2000" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none @error('content') border-danger-500 @enderror"
                    placeholder="Jelaskan laporan Anda secara detail...">{{ old('content') }}</textarea>
                <div class="flex justify-between mt-1">
                    <p class="text-sm text-gray-500">Minimal 20, maksimal 2000 karakter</p>
                    <p class="text-sm text-gray-500"><span id="charCount">0</span>/2000</p>
                </div>
                @error('content')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
            </div>

            <!-- Attachment -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lampiran (Opsional)</label>
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-primary-300 transition-colors">
                    <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf" class="hidden" id="attachment">
                    <label for="attachment" class="cursor-pointer">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-gray-600">Klik untuk upload file</p>
                        <p class="text-sm text-gray-500">JPG, PNG, atau PDF (maks 3MB)</p>
                    </label>
                    <p id="fileName" class="mt-2 text-sm text-primary-600 font-medium hidden"></p>
                </div>
                @error('attachment')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-medium">Klasifikasi Otomatis</p>
                        <p class="mt-1">Laporan Anda akan dianalisis secara otomatis untuk klasifikasi awal.</p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('reports.index') }}" class="px-6 py-3 text-gray-600 hover:text-gray-800 font-medium">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>

    <script>
        // Character counter
        const content = document.getElementById('content');
        const charCount = document.getElementById('charCount');
        content.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        // File name display
        const fileInput = document.getElementById('attachment');
        const fileName = document.getElementById('fileName');
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
                fileName.classList.remove('hidden');
            }
        });

        // Template functions
        function toggleTemplates() {
            const grid = document.getElementById('templateGrid');
            const btn = document.getElementById('toggleBtn');
            if (grid.classList.contains('hidden')) {
                grid.classList.remove('hidden');
                btn.textContent = 'Sembunyikan';
            } else {
                grid.classList.add('hidden');
                btn.textContent = 'Tampilkan Template';
            }
        }

        function applyTemplate(id, title, content) {
            // Fill in the form fields (category is auto-detected by AI)
            document.getElementById('title').value = title;
            document.getElementById('content').value = content;
            
            // Update character count
            document.getElementById('charCount').textContent = content.length;
            
            // Scroll to form
            document.querySelector('form').scrollIntoView({ behavior: 'smooth', block: 'start' });
            
            // Show success feedback
            const templateGrid = document.getElementById('templateGrid');
            templateGrid.insertAdjacentHTML('beforebegin', 
                '<div id="templateFeedback" class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">' +
                '✓ Template diterapkan! Sesuaikan isi sesuai kebutuhan Anda.' +
                '</div>'
            );
            
            // Remove feedback after 3 seconds
            setTimeout(() => {
                const feedback = document.getElementById('templateFeedback');
                if (feedback) feedback.remove();
            }, 3000);
        }

        // ===== Multi-Select Tag System for Accused Users =====
        const selectedAccused = new Map(); // id => {name, role}
        const searchInput = document.getElementById('accusedSearch');
        const dropdown = document.getElementById('accusedDropdown');
        const tagsContainer = document.getElementById('selectedTags');
        const hiddenInputs = document.getElementById('hiddenInputs');
        const container = document.getElementById('accusedContainer');

        if (searchInput && dropdown) {
            // Show dropdown on focus
            searchInput.addEventListener('focus', () => {
                dropdown.classList.remove('hidden');
                filterOptions('');
            });

            // Filter options on input
            searchInput.addEventListener('input', (e) => {
                filterOptions(e.target.value.toLowerCase());
            });

            // Click on option to select
            dropdown.addEventListener('click', (e) => {
                const option = e.target.closest('.accused-option');
                if (option) {
                    const id = option.dataset.id;
                    const name = option.dataset.name;
                    const role = option.dataset.role;
                    
                    if (!selectedAccused.has(id)) {
                        addTag(id, name, role);
                    }
                    searchInput.value = '';
                    searchInput.focus();
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!container.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });

            // Handle keyboard navigation
            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && searchInput.value === '' && selectedAccused.size > 0) {
                    // Remove last tag
                    const lastId = Array.from(selectedAccused.keys()).pop();
                    removeTag(lastId);
                }
            });
        }

        function filterOptions(query) {
            const options = dropdown.querySelectorAll('.accused-option');
            options.forEach(option => {
                const name = option.dataset.name.toLowerCase();
                const isSelected = selectedAccused.has(option.dataset.id);
                
                if (name.includes(query) && !isSelected) {
                    option.classList.remove('hidden');
                } else {
                    option.classList.add('hidden');
                }
            });
        }

        function addTag(id, name, role) {
            selectedAccused.set(id, { name, role });
            
            // Create tag element
            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-700';
            tag.dataset.id = id;
            tag.innerHTML = `
                ${name}
                <button type="button" class="ml-1 hover:text-primary-900" onclick="removeTag('${id}')">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            `;
            tagsContainer.appendChild(tag);
            
            // Create hidden input
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'accused_user_ids[]';
            input.value = id;
            input.id = `accused_input_${id}`;
            hiddenInputs.appendChild(input);
            
            // Update dropdown visibility
            filterOptions(searchInput.value.toLowerCase());
        }

        function removeTag(id) {
            selectedAccused.delete(id);
            
            // Remove tag element
            const tag = tagsContainer.querySelector(`[data-id="${id}"]`);
            if (tag) tag.remove();
            
            // Remove hidden input
            const input = document.getElementById(`accused_input_${id}`);
            if (input) input.remove();
            
            // Update dropdown visibility
            filterOptions(searchInput.value.toLowerCase());
        }
    </script>
</x-layouts.app>
