<x-layouts.app title="{{ $school->name }}">
    <div class="mb-6">
        <a href="{{ route('admin.schools') }}" class="text-primary-600 hover:text-primary-700 text-sm inline-flex items-center mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Sekolah
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $school->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Detail informasi sekolah</p>
            </div>
            @if($school->subscription_status === 'suspended')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                Akun Dinonaktifkan
            </span>
            @endif
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- School Info -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Informasi Sekolah</h3>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $school->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">NPSN</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $school->npsn ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Telepon</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $school->phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Kota</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $school->city ?? '-' }}, {{ $school->province ?? '-' }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Alamat</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $school->address ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Subscription Details -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Detail Langganan</h3>
                
                @if($currentSubscription)
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Paket Aktif</dt>
                        <dd class="font-semibold text-gray-900 dark:text-gray-100">{{ $currentSubscription->package->name ?? 'N/A' }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Status</dt>
                        <dd>
                            <span class="{{ $currentSubscription->status_color }} px-2.5 py-0.5 rounded-full text-xs font-medium">
                                {{ ucfirst($currentSubscription->status) }}
                            </span>
                        </dd>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Berlaku Hingga</dt>
                        <dd class="font-semibold text-gray-900 dark:text-gray-100">{{ $currentSubscription->expires_at?->format('d/m/Y') ?? '-' }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Sisa Hari</dt>
                        <dd class="font-semibold text-gray-900 dark:text-gray-100">{{ $currentSubscription->remaining_days }} hari</dd>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Dibayar</dt>
                        <dd class="font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($currentSubscription->amount_paid ?? 0, 0, ',', '.') }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Mulai</dt>
                        <dd class="font-semibold text-gray-900 dark:text-gray-100">{{ $currentSubscription->starts_at?->format('d/m/Y') ?? '-' }}</dd>
                    </div>
                </div>
                @else
                <div class="p-6 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-center mb-4">
                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada langganan aktif</p>
                </div>
                @endif

                <!-- Manual Update Subscription -->
                <div class="border-t border-gray-100 dark:border-gray-700 pt-4 mt-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Update Manual</h4>
                    <form method="POST" action="{{ route('admin.school.update-subscription', $school) }}" class="flex flex-wrap gap-3">
                        @csrf
                        @method('PUT')
                        <select name="subscription_status" class="px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                            <option value="trial" {{ $school->subscription_status === 'trial' ? 'selected' : '' }}>Trial</option>
                            <option value="active" {{ $school->subscription_status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ $school->subscription_status === 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="suspended" {{ $school->subscription_status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        <input type="date" name="trial_ends_at" value="{{ $school->trial_ends_at?->format('Y-m-d') }}" 
                            class="px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm" placeholder="Trial End Date">
                        <button type="submit" class="btn-secondary text-sm">Update Status</button>
                    </form>
                </div>
            </div>

            <!-- Payment History -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Riwayat Pembayaran</h3>
                @if($payments && $payments->count() > 0)
                <div class="space-y-3">
                    @foreach($payments as $payment)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-gray-100">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($payment->status === 'paid') bg-green-100 text-green-700
                            @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-6">Belum ada pembayaran</p>
                @endif
            </div>

            <!-- Users -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Pengguna ({{ $school->users->count() }})</h3>
                <div class="space-y-3">
                    @foreach($school->users->take(10) as $user)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-primary-700 font-semibold text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200 capitalize">
                            {{ str_replace('_', ' ', $user->role) }}
                        </span>
                    </div>
                    @endforeach
                    @if($school->users->count() > 10)
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center pt-2">... dan {{ $school->users->count() - 10 }} pengguna lainnya</p>
                    @endif
                </div>
            </div>

            <!-- Recent Reports -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Laporan Terbaru</h3>
                @if($recentReports->count() > 0)
                <div class="space-y-3">
                    @foreach($recentReports as $report)
                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $report->title }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $report->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-6">Belum ada laporan</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Status Akun</h3>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                    @if($school->subscription_status === 'active') bg-green-100 text-green-700
                    @elseif($school->subscription_status === 'trial') bg-yellow-100 text-yellow-700
                    @elseif($school->subscription_status === 'suspended') bg-red-100 text-red-700
                    @else bg-gray-100 text-gray-700 @endif">
                    {{ ucfirst($school->subscription_status) }}
                </span>
                @if($school->trial_ends_at && $school->subscription_status === 'trial')
                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">Trial berakhir: {{ $school->trial_ends_at->format('d/m/Y') }}</p>
                @endif
                
                @if($school->subscription_status === 'suspended' && isset($school->settings['suspended_at']))
                <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <p class="text-sm text-red-700 dark:text-red-300">
                        <strong>Dinonaktifkan:</strong> {{ \Carbon\Carbon::parse($school->settings['suspended_at'])->format('d/m/Y H:i') }}
                    </p>
                    @if(isset($school->settings['suspended_reason']))
                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $school->settings['suspended_reason'] }}</p>
                    @endif
                </div>
                @endif
            </div>

            <!-- Stats -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Statistik</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Total Pengguna</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $school->users->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Total Laporan</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $reportsCount }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Langganan</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $school->subscriptions->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Terdaftar</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $school->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Aksi</h3>
                
                @if($school->subscription_status === 'suspended')
                <form method="POST" action="{{ route('admin.school.toggle-status', $school) }}" class="mb-3">
                    @csrf
                    <input type="hidden" name="action" value="activate">
                    <button type="submit" class="w-full btn-primary flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Aktifkan Kembali
                    </button>
                </form>
                @else
                <button type="button" onclick="showSuspendModal()" class="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center mb-3">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    Nonaktifkan Sekolah
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Suspend Modal -->
    <div id="suspend-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Nonaktifkan Sekolah</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Sekolah ini tidak akan dapat mengakses sistem selama dinonaktifkan.</p>
            
            <form method="POST" action="{{ route('admin.school.toggle-status', $school) }}">
                @csrf
                <input type="hidden" name="action" value="suspend">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alasan (opsional)</label>
                    <textarea name="reason" rows="3" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="Masukkan alasan penonaktifan..."></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="hideSuspendModal()" class="flex-1 btn-secondary">Batal</button>
                    <button type="submit" class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors">Nonaktifkan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showSuspendModal() {
            document.getElementById('suspend-modal').classList.remove('hidden');
        }
        function hideSuspendModal() {
            document.getElementById('suspend-modal').classList.add('hidden');
        }
        // Close modal when clicking outside
        document.getElementById('suspend-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideSuspendModal();
            }
        });
    </script>
</x-layouts.app>
