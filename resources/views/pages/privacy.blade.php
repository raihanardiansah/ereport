<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kebijakan Privasi e-Report - Sistem Laporan Siswa Digital">
    <title>Kebijakan Privasi - e-Report</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #3CB371 0%, #00B4D8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="antialiased bg-slate-950 text-white">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-slate-950/80 backdrop-blur-lg border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-2">
                        <img src="https://i.ibb.co.com/bgHHDbVR/Logo-1-1.png" alt="Logo" class="h-10 w-auto">
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Beranda</a>
                    <a href="/about" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Tentang</a>
                    <a href="/blog" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Blog</a>
                    <div class="h-5 w-px bg-white/10"></div>
                    <a href="/login" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Masuk</a>
                    <a href="/register" class="text-sm font-medium bg-white text-slate-900 px-5 py-2.5 rounded-full hover:bg-gray-100 transition-colors">Daftar Sekarang</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-300 hover:text-white p-2">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-slate-950 border-t border-white/10">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="/" class="block px-3 py-3 text-base font-medium text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Beranda</a>
                <a href="/about" class="block px-3 py-3 text-base font-medium text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Tentang</a>
                <a href="/blog" class="block px-3 py-3 text-base font-medium text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Blog</a>
                <div class="my-4 border-t border-white/10"></div>
                <a href="/login" class="block px-3 py-3 text-base font-medium text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Masuk</a>
                <a href="/register" class="block px-3 py-3 text-base font-medium text-emerald-400 hover:text-emerald-300 hover:bg-white/5 rounded-lg">Daftar Sekarang</a>
            </div>
        </div>
    </nav>

    <script>
        // Mobile Menu Toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>

    <!-- Main Content -->
    <main class="pt-32 pb-20 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <h1 class="text-5xl font-bold mb-6">
                    <span class="gradient-text">Kebijakan Privasi</span>
                </h1>
                <p class="text-xl text-white/70">
                    Terakhir diperbarui: {{ date('d F Y') }}
                </p>
            </div>

            <!-- Content -->
            <div class="prose prose-invert prose-lg max-w-none">
                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">Pendahuluan</h2>
                    <p class="text-white/80 leading-relaxed">
                        PT. Kreasi Digital Creative Minds Indonesia ("kami", "e-Report") berkomitmen untuk melindungi privasi Anda. 
                        Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda 
                        saat menggunakan platform e-Report.
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">1. Informasi yang Kami Kumpulkan</h2>
                    <p class="text-white/80 leading-relaxed mb-4">
                        Kami mengumpulkan beberapa jenis informasi untuk menyediakan dan meningkatkan layanan kami:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-white/80">
                        <li><strong class="text-white">Informasi Akun:</strong> Nama, email, nomor telepon, dan informasi sekolah</li>
                        <li><strong class="text-white">Informasi Laporan:</strong> Data laporan siswa, kategori pelanggaran, dan status penanganan</li>
                        <li><strong class="text-white">Informasi Teknis:</strong> Alamat IP, jenis browser, sistem operasi, dan log aktivitas</li>
                        <li><strong class="text-white">Informasi Pembayaran:</strong> Data transaksi langganan (diproses melalui Midtrans)</li>
                    </ul>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">2. Penggunaan Informasi</h2>
                    <p class="text-white/80 leading-relaxed mb-4">
                        Kami menggunakan informasi yang dikumpulkan untuk:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-white/80">
                        <li>Menyediakan dan memelihara layanan e-Report</li>
                        <li>Memproses dan mengelola laporan siswa</li>
                        <li>Mengirimkan notifikasi terkait status laporan</li>
                        <li>Memproses pembayaran langganan</li>
                        <li>Meningkatkan keamanan dan mencegah penyalahgunaan</li>
                        <li>Menganalisis penggunaan untuk peningkatan layanan</li>
                        <li>Berkomunikasi dengan Anda tentang layanan kami</li>
                    </ul>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">3. Keamanan Data</h2>
                    <p class="text-white/80 leading-relaxed mb-4">
                        Kami menerapkan langkah-langkah keamanan yang sesuai untuk melindungi data Anda:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-white/80">
                        <li>Enkripsi data saat transit menggunakan SSL/TLS</li>
                        <li>Enkripsi data sensitif di database</li>
                        <li>Kontrol akses berbasis peran (Role-Based Access Control)</li>
                        <li>Backup data otomatis secara berkala</li>
                        <li>Audit log untuk melacak aktivitas sistem</li>
                        <li>Pemantauan keamanan 24/7</li>
                    </ul>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">4. Berbagi Informasi</h2>
                    <p class="text-white/80 leading-relaxed mb-4">
                        Kami tidak menjual atau menyewakan informasi pribadi Anda kepada pihak ketiga. 
                        Kami hanya membagikan informasi dalam kondisi berikut:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-white/80">
                        <li><strong class="text-white">Penyedia Layanan:</strong> Dengan mitra tepercaya seperti Midtrans untuk pemrosesan pembayaran</li>
                        <li><strong class="text-white">Kepatuhan Hukum:</strong> Jika diwajibkan oleh hukum atau proses hukum yang sah</li>
                        <li><strong class="text-white">Persetujuan Anda:</strong> Dengan persetujuan eksplisit Anda</li>
                    </ul>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">5. Hak Anda</h2>
                    <p class="text-white/80 leading-relaxed mb-4">
                        Anda memiliki hak untuk:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-white/80">
                        <li>Mengakses dan mendapatkan salinan data pribadi Anda</li>
                        <li>Memperbarui atau mengoreksi informasi yang tidak akurat</li>
                        <li>Menghapus data pribadi Anda (dengan batasan tertentu)</li>
                        <li>Membatasi pemrosesan data pribadi Anda</li>
                        <li>Mengajukan keberatan terhadap pemrosesan data</li>
                        <li>Portabilitas data ke platform lain</li>
                    </ul>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">6. Cookies dan Teknologi Pelacakan</h2>
                    <p class="text-white/80 leading-relaxed">
                        Kami menggunakan cookies dan teknologi serupa untuk meningkatkan pengalaman pengguna, 
                        menganalisis trafik, dan menyimpan preferensi Anda. Anda dapat mengatur browser Anda 
                        untuk menolak cookies, namun beberapa fitur mungkin tidak berfungsi dengan baik.
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">7. Penyimpanan Data</h2>
                    <p class="text-white/80 leading-relaxed">
                        Kami menyimpan data pribadi Anda selama akun Anda aktif atau selama diperlukan untuk 
                        menyediakan layanan. Data akan dihapus sesuai dengan kebijakan retensi data kami atau 
                        sesuai permintaan Anda, kecuali jika kami diwajibkan untuk menyimpannya berdasarkan 
                        peraturan perundang-undangan yang berlaku.
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">8. Privasi Anak-anak</h2>
                    <p class="text-white/80 leading-relaxed">
                        Layanan kami ditujukan untuk digunakan oleh institusi pendidikan. Data siswa di bawah 
                        umur 18 tahun harus dikelola oleh pihak sekolah yang berwenang. Kami tidak secara 
                        sengaja mengumpulkan informasi pribadi dari anak-anak tanpa persetujuan orang tua atau wali.
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">9. Perubahan Kebijakan</h2>
                    <p class="text-white/80 leading-relaxed">
                        Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Perubahan akan 
                        diumumkan melalui email atau notifikasi di platform. Penggunaan layanan yang 
                        berkelanjutan setelah perubahan berarti Anda menerima kebijakan yang diperbarui.
                    </p>
                </div>

                <div class="bg-gradient-to-r from-emerald-500/10 to-cyan-500/10 border border-emerald-500/30 rounded-2xl p-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">10. Hubungi Kami</h2>
                    <p class="text-white/80 leading-relaxed mb-4">
                        Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini atau ingin menggunakan 
                        hak-hak Anda, silakan hubungi kami:
                    </p>
                    <div class="space-y-2 text-white/80">
                        <p><strong class="text-white">Email:</strong> <a href="mailto:support@ereport.systems" class="text-emerald-400 hover:text-emerald-300">support@ereport.systems</a></p>
                        <p><strong class="text-white">WhatsApp:</strong> <a href="https://wa.me/628990772526" class="text-emerald-400 hover:text-emerald-300">+62 899 077 2526</a></p>
                        <p><strong class="text-white">Perusahaan:</strong> PT. Kreasi Digital Creative Minds Indonesia</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-8 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500">© {{ date('Y') }} e-Report. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="/privacy" class="text-gray-400 hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="/terms" class="text-gray-400 hover:text-white transition-colors">Syarat & Ketentuan</a>
                    <a href="/about" class="text-gray-400 hover:text-white transition-colors">Tentang</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
