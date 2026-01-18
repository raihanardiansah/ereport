<?php if (isset($component)) { $__componentOriginalfa710ee477a7171fb238cadd060c5959 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfa710ee477a7171fb238cadd060c5959 = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App::resolve(['title' => 'Audit Trail'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layouts\App::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Audit Trail</h1>
            <p class="text-gray-600 mt-1">Riwayat aktivitas dan akses data sensitif</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Cari Laporan/Action</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="ID Laporan atau Action..." class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Action</label>
                <select name="action" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Action</option>
                    <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($action); ?>" <?php echo e(request('action') == $action ? 'selected' : ''); ?>><?php echo e(ucwords(str_replace('_', ' ', $action))); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">User</label>
                <select name="user_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua User</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full btn-primary py-2 text-sm">Filter</button>
            </div>
        </form>
    </div>

    <!-- Audit Log Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 font-medium">Waktu</th>
                        <th class="px-6 py-3 font-medium">User</th>
                        <th class="px-6 py-3 font-medium">Action</th>
                        <th class="px-6 py-3 font-medium">Laporan</th>
                        <th class="px-6 py-3 font-medium">Detail</th>
                        <th class="px-6 py-3 font-medium">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $auditLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500">
                            <?php echo e($log->created_at->format('d M Y H:i:s')); ?>

                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gray-200 overflow-hidden shrink-0">
                                    <img src="<?php echo e($log->user->avatar_url); ?>" alt="" class="w-full h-full object-cover">
                                </div>
                                <span class="font-medium text-gray-900"><?php echo e($log->user->name); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                <?php if(in_array($log->action, ['user_banned', 'report_deleted'])): ?> bg-red-100 text-red-700
                                <?php elseif(in_array($log->action, ['status_changed', 'urgency_changed'])): ?> bg-yellow-100 text-yellow-700
                                <?php elseif($log->action == 'report_viewed'): ?> bg-blue-50 text-blue-600
                                <?php else: ?> bg-gray-100 text-gray-700 <?php endif; ?>">
                                <?php echo e(ucwords(str_replace('_', ' ', $log->action))); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($log->report): ?>
                            <a href="<?php echo e(route('reports.show', $log->report)); ?>" class="text-primary-600 hover:text-primary-700 hover:underline">
                                #<?php echo e($log->report->id); ?> - <?php echo e(Str::limit($log->report->title, 20)); ?>

                            </a>
                            <?php else: ?>
                            <span class="text-gray-400 italic">Terhapus</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            <?php if($log->old_values || $log->new_values): ?>
                                <button onclick="alert('Detail perubahan:\nOld: <?php echo e(json_encode($log->old_values)); ?>\nNew: <?php echo e(json_encode($log->new_values)); ?>')" class="text-xs text-blue-600 hover:underline">
                                    Lihat Perubahan
                                </button>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-gray-500 font-mono text-xs">
                            <?php echo e($log->ip_address); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Belum ada log audit.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($auditLogs->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-100">
            <?php echo e($auditLogs->links()); ?>

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
<?php /**PATH /var/www/html/resources/views/audit/index.blade.php ENDPATH**/ ?>