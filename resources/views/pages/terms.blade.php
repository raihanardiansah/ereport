<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Syarat dan Ketentuan e-Report - Sistem Laporan Siswa Digital">
    <title>Syarat dan Ketentuan - e-Report</title>
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
                <div class="flex items-center">
                    <a href="/">
                        <img src="https://i.ibb.co.com/bgHHDbVR/Logo-1-1.png" alt="Logo" class="h-11">
                    </a>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="/" class="text-gray-300 hover:text-white font-medium transition-colors">Beranda</a>
                    <a href="/login" class="text-gray-300 hover:text-white font-medium transition-colors">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-32 pb-20 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <h1 class="text-5xl font-bold mb-6">
                    <span class="gradient-text">Syarat dan Ketentuan</span>
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
                        Selamat datang di e-Report. Dengan mengakses dan menggunakan platform e-Report, 
                        Anda setuju untuk terikat dengan Syarat dan Ketentuan berikut. Harap baca dengan 
                        seksama sebelum menggunakan layanan kami.
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">1. Penerimaan Ketentuan</h2>
                    <p class="text-white/80 leading-relaxed mb-4">
                        Dengan mendaftar, mengakses, atau menggunakan layanan e-Report, Anda menyatakan bahwa:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-white/80">
                        <li>Anda telah membaca dan memahami Syarat dan Ketentuan ini</li>
                        <li>Anda setuju untuk mematuhi semua ketentuan yang berlaku</li>
                        <li>Anda memiliki wewenang untuk mengikat institusi pendidikan yang Anda wakili</li>
                        <li>Anda berusia minimal 18 tahun atau memiliki izin dari orang tua/wali</li>
                    </ul>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">2. Deskripsi Layanan</h2>
                    <p class="text-white/80 leading-relaxed mb-4">
                        e-Report adalah platform berbasis web untuk manajemen laporan siswa digital yang menyediakan:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-white/80">
                        <li>Sistem pelaporan pelanggaran siswa secara digital</li>
                        <li>Dashboard analytics untuk monitoring laporan</li>
                        <li>Manajemen kasus siswa dan guru</li>
                        <li>Notifikasi real-time untuk update status laporan</li>
                        <li>Export data dan laporan dalam format PDF</li>
                        <li>Multi-role access control untuk berbagai pengguna</li>
                    </ul>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">3. Akun Pengguna</h2>
                    <h3 class="text-xl font-semibold mb-3 text-white">3.1 Pendaftaran</h3>
                    <p class="text-white/80 leading-relaxed mb-4">
                        Untuk menggunakan layanan, Anda harus membuat akun dengan memberikan informasi yang 
                        akurat, lengkap, dan terkini. Anda bertanggung jawab untuk menjaga kerahasiaan 
                        kredensial akun Anda.
                    </p>
                    <h3 class="text-xl font-semibold mb-3 text-white">3.2 Tanggung Jawab Akun</h3>
                    <ul class="list-disc list-inside space-y-2 text-white/80">
                        <li>Anda bertanggung jawab atas semua aktivitas yang terjadi di akun Anda</li>
                        <li>Anda harus segera memberitahu kami jika terjadi penggunaan tidak sah</li>
                        <li>Anda tidak boleh berbagi kredensial akun dengan pihak lain</li>
                        <li>Satu akun hanya untuk satu institusi pendidikan</li>
                    </ul>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">4. Penggunaan Layanan</h2>
                    <h3 class="text-xl font-semibold mb-3 text-white">4.1 Penggunaan yang Diizinkan</h3>
                    <p class="text-white/80 leading-relaxed mb-4">
                        Anda setuju untuk menggunakan layanan hanya untuk tujuan yang sah dan sesuai dengan 
                        peraturan perundang-undangan yang berlaku.
                    </p>
                    <h3 class="text-xl font-semibold mb-3 text-white">4.2 Penggunaan yang Dilarang</h3>
                    <p class="text-white/80 leading-relaxed mb-4">Anda TIDAK BOLEH:</p>
                    <ul class="list-disc list-inside space-y-2 text-white/80">
                        <li>Menggunakan layanan untuk tujuan ilegal atau tidak sah</li>
                        <li>Mengunggah konten yang melanggar hak cipta atau hak kekayaan intelektual</li>
                        <li>Menyebarkan malware, virus, atau kode berbahaya lainnya</li>
                        <li>Melakukan reverse engineering, decompile, atau disassemble platform</li>
                        <li>Mengakses sistem tanpa otorisasi atau mencoba menembus keamanan</li>
                        <li>Menggunakan bot, scraper, atau alat otomatis lainnya</li>
                        <li>Mengganggu atau merusak integritas atau kinerja layanan</li>
                        <li>Menyalahgunakan data pribadi siswa atau pengguna lain</li>
                    </ul>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">5. Langganan dan Pembayaran</h2>
                    <h3 class="text-xl font-semibold mb-3 text-white">5.1 Paket Langganan</h3>
                    <p class="text-white/80 leading-relaxed mb-4">
                        e-Report menawarkan berbagai paket langganan dengan fitur dan batasan yang berbeda. 
                        Semua paket dimulai dengan trial gratis 7 hari.
                    </p>
                    <h3 class="text-xl font-semibold mb-3 text-white">5.2 Pembayaran</h3>
                    <ul class="list-disc list-inside space-y-2 text-white/80">
                        <li>Pembayaran diproses melalui Midtrans (penyedia pembayaran pihak ketiga)</li>
                        <li>Harga yang tercantum sudah termasuk pajak yang berlaku</li>
                        <li>Pembayaran dilakukan di muka untuk periode langganan yang dipilih</li>
                        <li>Kami berhak mengubah harga dengan pemberitahuan 30 hari sebelumnya</li>
                    </ul>
                    <h3 class="text-xl font-semibold mb-3 text-white">5.3 Pembaruan Otomatis</h3>
                    <p class="text-white/80 leading-relaxed">
                        Langganan akan diperpanjang secara otomatis kecuali Anda membatalkannya sebelum 
                        periode berikutnya dimulai.
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">6. Pembatalan dan Pengembalian Dana</h2>
                    <h3 class="text-xl font-semibold mb-3 text-white">6.1 Pembatalan</h3>
                    <p class="text-white/80 leading-relaxed mb-4">
                        Anda dapat membatalkan langganan kapan saja melalui dashboard akun Anda. 
                        Pembatalan akan berlaku pada akhir periode langganan yang sedang berjalan.
                    </p>
                    <h3 class="text-xl font-semibold mb-3 text-white">6.2 Pengembalian Dana</h3>
                    <p class="text-white/80 leading-relaxed">
                        Pengembalian dana dapat diberikan dalam waktu 7 hari setelah pembayaran jika 
                        Anda belum menggunakan layanan secara signifikan. Setelah periode tersebut, 
                        pembayaran tidak dapat dikembalikan.
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">7. Hak Kekayaan Intelektual</h2>
                    <p class="text-white/80 leading-relaxed mb-4">
                        Semua hak kekayaan intelektual dalam platform e-Report, termasuk namun tidak 
                        terbatas pada kode sumber, desain, logo, dan konten, adalah milik PT. Kreasi 
                        Digital Creative Minds Indonesia.
                    </p>
                    <p class="text-white/80 leading-relaxed">
                        Anda diberikan lisensi terbatas, non-eksklusif, dan tidak dapat dipindahtangankan 
                        untuk menggunakan layanan sesuai dengan Syarat dan Ketentuan ini.
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">8. Ketersediaan Layanan</h2>
                    <p class="text-white/80 leading-relaxed mb-4">
                        Kami berusaha untuk menjaga layanan tetap tersedia 24/7 dengan uptime 99.9%, namun:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-white/80">
                        <li>Kami tidak menjamin layanan akan selalu tersedia tanpa gangguan</li>
                        <li>Pemeliharaan terjadwal akan diumumkan sebelumnya</li>
                        <li>Kami tidak bertanggung jawab atas downtime yang disebabkan oleh pihak ketiga</li>
                        <li>Kami berhak menangguhkan layanan untuk pemeliharaan darurat</li>
                    </ul>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">9. Pembatasan Tanggung Jawab</h2>
                    <p class="text-white/80 leading-relaxed mb-4">
                        Dalam batas maksimum yang diizinkan oleh hukum:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-white/80">
                        <li>Layanan disediakan "sebagaimana adanya" tanpa jaminan apapun</li>
                        <li>Kami tidak bertanggung jawab atas kerugian langsung, tidak langsung, atau konsekuensial</li>
                        <li>Tanggung jawab kami terbatas pada jumlah yang Anda bayarkan dalam 12 bulan terakhir</li>
                        <li>Kami tidak bertanggung jawab atas kehilangan data akibat kesalahan pengguna</li>
                    </ul>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">10. Penangguhan dan Penghentian</h2>
                    <p class="text-white/80 leading-relaxed mb-4">
                        Kami berhak menangguhkan atau menghentikan akses Anda jika:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-white/80">
                        <li>Anda melanggar Syarat dan Ketentuan ini</li>
                        <li>Anda gagal melakukan pembayaran</li>
                        <li>Kami mencurigai aktivitas penipuan atau penyalahgunaan</li>
                        <li>Diwajibkan oleh hukum atau perintah pengadilan</li>
                    </ul>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">11. Perubahan Ketentuan</h2>
                    <p class="text-white/80 leading-relaxed">
                        Kami dapat mengubah Syarat dan Ketentuan ini kapan saja. Perubahan material akan 
                        diumumkan melalui email atau notifikasi di platform minimal 30 hari sebelum berlaku. 
                        Penggunaan layanan yang berkelanjutan setelah perubahan berarti Anda menerima 
                        ketentuan yang diperbarui.
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">12. Hukum yang Berlaku</h2>
                    <p class="text-white/80 leading-relaxed">
                        Syarat dan Ketentuan ini diatur oleh dan ditafsirkan sesuai dengan hukum Republik 
                        Indonesia. Setiap sengketa yang timbul akan diselesaikan melalui pengadilan yang 
                        berwenang di Indonesia.
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">13. Ketentuan Umum</h2>
                    <ul class="list-disc list-inside space-y-2 text-white/80">
                        <li><strong class="text-white">Keterpisahan:</strong> Jika ada ketentuan yang tidak dapat dilaksanakan, ketentuan lainnya tetap berlaku</li>
                        <li><strong class="text-white">Pengalihan:</strong> Anda tidak dapat mengalihkan hak Anda tanpa persetujuan tertulis kami</li>
                        <li><strong class="text-white">Keseluruhan Perjanjian:</strong> Ini merupakan keseluruhan perjanjian antara Anda dan kami</li>
                        <li><strong class="text-white">Tidak Ada Pengesampingan:</strong> Kegagalan kami menegakkan hak tidak berarti pengesampingan hak tersebut</li>
                    </ul>
                </div>

                <div class="bg-gradient-to-r from-emerald-500/10 to-cyan-500/10 border border-emerald-500/30 rounded-2xl p-8">
                    <h2 class="text-2xl font-bold mb-4 text-white">14. Hubungi Kami</h2>
                    <p class="text-white/80 leading-relaxed mb-4">
                        Jika Anda memiliki pertanyaan tentang Syarat dan Ketentuan ini, silakan hubungi kami:
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
