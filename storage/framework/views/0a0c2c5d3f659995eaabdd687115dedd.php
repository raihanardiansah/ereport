<?php if (isset($component)) { $__componentOriginalfa710ee477a7171fb238cadd060c5959 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfa710ee477a7171fb238cadd060c5959 = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App::resolve(['title' => 'Papan Peringkat'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layouts\App::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">🏆 Papan Peringkat Sekolah</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Siapa siswa paling aktif berkontribusi untuk sekolah?</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Main Leaderboard -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Top 10 Kontributor</h2>
                    <span class="text-sm text-gray-500">Update Real-time</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Streak</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Poin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <?php $__currentLoopData = $leaderboard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $player): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors <?php echo e($player->id === $user->id ? 'bg-primary-50 dark:bg-primary-900/20' : ''); ?>">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <?php if($index === 0): ?>
                                            <span class="text-2xl mr-2">🥇</span>
                                        <?php elseif($index === 1): ?>
                                            <span class="text-2xl mr-2">🥈</span>
                                        <?php elseif($index === 2): ?>
                                            <span class="text-2xl mr-2">🥉</span>
                                        <?php else: ?>
                                            <span class="text-lg font-bold text-gray-400 w-8 text-center"><?php echo e($index + 1); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full object-cover border-2 border-white dark:border-gray-600 shadow-sm flex-shrink-0" 
                                            src="<?php echo e($player->avatar_url); ?>" 
                                            alt="<?php echo e($player->name); ?>"
                                            onerror="this.src='https://ui-avatars.com/api/?name=<?php echo e(urlencode($player->name)); ?>&color=7F9CF5&background=EBF4FF'">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100 flex items-center">
                                                <?php echo e($player->name); ?>

                                                <?php if($player->id === $user->id): ?>
                                                    <span class="ml-2 px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-800">Anda</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-sm text-gray-500"><?php echo e($player->getRoleDisplayName()); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <?php if($player->current_streak > 0): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            🔥 <?php echo e($player->current_streak); ?> Hari
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <span class="text-lg font-bold text-primary-600 dark:text-primary-400"><?php echo e(number_format($player->total_points)); ?></span>
                                    <span class="text-xs text-gray-500">pts</span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if(auth()->user()->role === 'siswa'): ?>
        <!-- My Stats -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Points Card -->
            <div class="bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                <div class="relative z-10">
                    <h3 class="text-primary-100 font-medium mb-1">Total Poin Anda</h3>
                    <div class="text-4xl font-bold mb-4"><?php echo e(number_format($user->total_points)); ?> pts</div>
                    <div class="flex items-center justify-between text-sm bg-white/10 rounded-lg p-3">
                        <span>Peringkat Sekolah</span>
                        <span class="font-bold">#<?php echo e($myRank); ?></span>
                    </div>
                </div>
            </div>

            <!-- Badges Collection -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <span class="mr-2">🏅</span> Koleksi Badge
                </h3>
                
                <?php if($myBadges->count() > 0): ?>
                    <div class="grid grid-cols-3 gap-4">
                        <?php $__currentLoopData = $myBadges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex flex-col items-center text-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group relative" title="<?php echo e($badge->description); ?>">
                            <div class="text-4xl mb-2 transform group-hover:scale-110 transition-transform">
                                <?php echo e($badge->icon); ?>

                            </div>
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300 line-clamp-2"><?php echo e($badge->name); ?></span>
                            
                            <!-- Tooltip -->
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-48 bg-gray-900 text-white text-xs rounded-lg p-2 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-20">
                                <?php echo e($badge->description); ?>

                                <div class="text-gray-400 mt-1">Diraih: <?php echo e(\Carbon\Carbon::parse($badge->pivot->earned_at)->format('d M Y')); ?></div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <div class="text-4xl mb-2 grayscale opacity-50">🏆</div>
                        <p class="text-sm">Belum ada badge yang diraih.</p>
                        <p class="text-xs mt-2">Kirim laporan untuk mulai mengumpulkan!</p>
                    </div>
                <?php endif; ?>
                
                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Cara Dapat Poin:</h4>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                        <li class="flex items-center">
                            <span class="text-green-500 mr-2">+10</span> Kirim Laporan
                        </li>
                        <li class="flex items-center">
                            <span class="text-blue-500 mr-2">+5</span> Laporan Selesai
                        </li>
                        <li class="flex items-center">
                            <span class="text-orange-500 mr-2">+2</span> Streak Harian
                        </li>
                        <li class="flex items-center">
                            <span class="text-purple-500 mr-2">+20</span> Laporan Pertama
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
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
<?php /**PATH /var/www/html/resources/views/leaderboard/index.blade.php ENDPATH**/ ?>