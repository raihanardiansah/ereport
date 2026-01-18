<?php if (isset($component)) { $__componentOriginalfa710ee477a7171fb238cadd060c5959 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfa710ee477a7171fb238cadd060c5959 = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App::resolve(['title' => 'Profil Sekolah'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layouts\App::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Profil Sekolah</h1>
        <p class="text-gray-600 mt-1">Kelola informasi sekolah Anda</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- School Info Card -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="<?php echo e(route('school.profile.update')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="grid sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2 bg-primary-50 border border-primary-200 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-primary-900">Kode Gabung Sekolah (Join Code)</h3>
                            <p class="text-xs text-primary-700 mt-1">Bagikan kode ini kepada siswa/guru untuk mendaftar mandiri.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <code class="px-3 py-1 bg-white border border-primary-300 rounded-lg text-lg font-mono font-bold text-primary-700 tracking-wider select-all"><?php echo e($school->join_code); ?></code>
                            <button type="button" onclick="navigator.clipboard.writeText('<?php echo e($school->join_code); ?>'); alert('Kode berhasil disalin!')" 
                                class="p-2 text-primary-600 hover:bg-primary-100 rounded-lg transition-colors" title="Salin Kode">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sekolah *</label>
                        <input type="text" name="name" value="<?php echo e(old('name', $school->name)); ?>" required maxlength="100"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-danger-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Sekolah *</label>
                        <input type="email" name="email" value="<?php echo e(old('email', $school->email)); ?>" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-danger-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NPSN</label>
                        <input type="text" name="npsn" value="<?php echo e(old('npsn', $school->npsn)); ?>" maxlength="20" pattern="[0-9]*"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent <?php $__errorArgs = ['npsn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['npsn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-danger-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                        <input type="tel" name="phone" value="<?php echo e(old('phone', $school->phone)); ?>"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="08123456789">
                        <p class="mt-1 text-xs text-gray-500">Format: 08xxx atau +628xxx</p>
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-danger-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                        <input type="url" name="website" value="<?php echo e(old('website', $school->website)); ?>"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="https://sekolah.sch.id">
                        <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-danger-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                        <select name="province" id="province-select"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Pilih Provinsi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten</label>
                        <select name="city" id="city-select" disabled
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-gray-50 text-gray-500">
                            <option value="">Pilih Provinsi Terlebih Dahulu</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea name="address" rows="3" maxlength="500"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"><?php echo e(old('address', $school->address)); ?></textarea>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Subscription Status -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Status Langganan</h3>
                
                <div class="flex items-center mb-4">
                    <?php if($school->subscription_status === 'active'): ?>
                        <span class="w-3 h-3 bg-primary-500 rounded-full mr-2"></span>
                        <span class="text-primary-700 font-medium">Aktif</span>
                    <?php elseif($school->subscription_status === 'trial'): ?>
                        <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                        <span class="text-yellow-700 font-medium">Masa Percobaan</span>
                    <?php else: ?>
                        <span class="w-3 h-3 bg-danger-500 rounded-full mr-2"></span>
                        <span class="text-danger-700 font-medium"><?php echo e(ucfirst($school->subscription_status)); ?></span>
                    <?php endif; ?>
                </div>

                <?php if($school->trial_ends_at): ?>
                    <p class="text-sm text-gray-600">
                        <?php if($school->subscription_status === 'trial'): ?>
                            Berakhir: <?php echo e($school->trial_ends_at->format('d/m/Y')); ?>

                            <span class="text-gray-500">(<?php echo e($school->trial_ends_at->diffForHumans()); ?>)</span>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>

                <a href="<?php echo e(route('subscriptions.index')); ?>" class="mt-4 block w-full btn-secondary text-center text-sm py-2">
                    Kelola Langganan
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Statistik</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Pengguna</span>
                        <span class="font-semibold text-gray-900"><?php echo e($school->users()->count()); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Guru</span>
                        <span class="font-semibold text-gray-900"><?php echo e($school->teachers()->count()); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Siswa</span>
                        <span class="font-semibold text-gray-900"><?php echo e($school->students()->count()); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const provinceSelect = document.getElementById('province-select');
        const citySelect = document.getElementById('city-select');
        
        const toTitleCase = (str) => {
            return str.replace(/\w\S*/g, (txt) => {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        };

        const oldProvince = "<?php echo e(old('province', $school->province)); ?>";
        const oldCity = "<?php echo e(old('city', $school->city)); ?>";

        try {
            const response = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
            let provinces = await response.json();
            provinces.sort((a, b) => a.name.localeCompare(b.name));

            provinces.forEach(province => {
                const option = document.createElement('option');
                const name = toTitleCase(province.name);
                option.value = name;
                option.textContent = name;
                option.dataset.id = province.id;
                
                if (oldProvince && oldProvince === name) {
                    option.selected = true;
                }
                
                provinceSelect.appendChild(option);
            });

            if (oldProvince) {
                const selectedOption = Array.from(provinceSelect.options).find(opt => opt.value === oldProvince);
                if (selectedOption) {
                    loadCities(selectedOption.dataset.id, oldCity);
                }
            }

        } catch (error) {
            console.error('Error fetching provinces:', error);
            provinceSelect.innerHTML += '<option value="">Gagal memuat data</option>';
        }

        provinceSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const provinceId = selectedOption.dataset.id;
            
            if (provinceId) {
                loadCities(provinceId);
            } else {
                citySelect.innerHTML = '<option value="">Pilih Provinsi Terlebih Dahulu</option>';
                citySelect.disabled = true;
                citySelect.classList.add('bg-gray-50', 'text-gray-500');
            }
        });

        async function loadCities(provinceId, selectedCity = null) {
            citySelect.innerHTML = '<option value="">Loading...</option>';
            citySelect.disabled = true;
            citySelect.classList.remove('bg-gray-50', 'text-gray-500');

            try {
                const response = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`);
                let regencies = await response.json();
                regencies.sort((a, b) => a.name.localeCompare(b.name));

                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                
                regencies.forEach(regency => {
                    const option = document.createElement('option');
                    const name = toTitleCase(regency.name);
                    option.value = name;
                    option.textContent = name;
                    
                    if (selectedCity && selectedCity === name) {
                        option.selected = true;
                    }
                    
                    citySelect.appendChild(option);
                });
                
                citySelect.disabled = false;
            } catch (error) {
                console.error('Error fetching regencies:', error);
                citySelect.innerHTML = '<option value="">Gagal memuat kota</option>';
            }
        }
    });
</script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfa710ee477a7171fb238cadd060c5959)): ?>
<?php $attributes = $__attributesOriginalfa710ee477a7171fb238cadd060c5959; ?>
<?php unset($__attributesOriginalfa710ee477a7171fb238cadd060c5959); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfa710ee477a7171fb238cadd060c5959)): ?>
<?php $component = $__componentOriginalfa710ee477a7171fb238cadd060c5959; ?>
<?php unset($__componentOriginalfa710ee477a7171fb238cadd060c5959); ?>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/school/profile.blade.php ENDPATH**/ ?>