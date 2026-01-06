<?php

namespace Database\Seeders;

use App\Models\ReportTemplate;
use Illuminate\Database\Seeder;

class ReportTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            // Perilaku (Behavior)
            [
                'name' => 'Perundungan/Bullying',
                'category' => 'perilaku',
                'title_template' => 'Laporan Perundungan: [Lokasi Kejadian]',
                'content_template' => "Saya ingin melaporkan kejadian perundungan yang terjadi di [lokasi].\n\n**Detail Kejadian:**\n- Tanggal: [tanggal]\n- Waktu: [waktu]\n- Lokasi: [lokasi spesifik]\n\n**Pihak yang Terlibat:**\n- Pelaku: [nama/ciri-ciri pelaku]\n- Korban: [nama korban jika bukan pelapor]\n\n**Kronologi Singkat:**\n[Jelaskan apa yang terjadi secara kronologis]\n\n**Dampak yang Dirasakan:**\n[Jelaskan dampak fisik/emosional yang dirasakan]\n\n**Saksi (jika ada):**\n[Nama saksi yang melihat kejadian]",
                'description' => 'Template untuk melaporkan kasus perundungan atau bullying',
                'icon' => '🚫',
                'is_global' => true,
            ],
            [
                'name' => 'Perkelahian',
                'category' => 'perilaku',
                'title_template' => 'Laporan Perkelahian di [Lokasi]',
                'content_template' => "Terjadi perkelahian yang perlu ditangani.\n\n**Detail Kejadian:**\n- Tanggal: [tanggal]\n- Waktu: [waktu]\n- Lokasi: [lokasi]\n\n**Pihak yang Terlibat:**\n[Daftar nama atau ciri-ciri siswa yang terlibat]\n\n**Kronologi:**\n[Jelaskan bagaimana perkelahian dimulai dan apa yang terjadi]\n\n**Kondisi Setelah Kejadian:**\n- Luka/cedera: [jelaskan jika ada]\n- Kerusakan: [jelaskan jika ada kerusakan barang]\n\n**Tindakan yang Sudah Dilakukan:**\n[Jelaskan jika sudah ada tindakan awal]",
                'description' => 'Template untuk melaporkan kasus perkelahian antar siswa',
                'icon' => '👊',
                'is_global' => true,
            ],
            [
                'name' => 'Pelanggaran Tata Tertib',
                'category' => 'perilaku',
                'title_template' => 'Pelanggaran Tata Tertib: [Jenis Pelanggaran]',
                'content_template' => "Melaporkan pelanggaran tata tertib sekolah.\n\n**Detail Pelanggaran:**\n- Jenis: [membolos/terlambat/seragam/dll]\n- Tanggal: [tanggal]\n- Waktu: [waktu]\n\n**Siswa yang Terlibat:**\n- Nama: [nama siswa]\n- Kelas: [kelas]\n\n**Deskripsi Pelanggaran:**\n[Jelaskan detail pelanggaran yang terjadi]\n\n**Frekuensi:**\n[Apakah ini pelanggaran pertama atau berulang?]\n\n**Catatan Tambahan:**\n[Informasi lain yang relevan]",
                'description' => 'Template untuk melaporkan pelanggaran tata tertib sekolah',
                'icon' => '📋',
                'is_global' => true,
            ],

            // Akademik (Academic)
            [
                'name' => 'Kesulitan Belajar',
                'category' => 'akademik',
                'title_template' => 'Kesulitan Belajar: [Nama Siswa/Mata Pelajaran]',
                'content_template' => "Melaporkan siswa yang mengalami kesulitan belajar.\n\n**Informasi Siswa:**\n- Nama: [nama siswa]\n- Kelas: [kelas]\n- Mata pelajaran yang sulit: [mata pelajaran]\n\n**Indikasi Kesulitan:**\n[Jelaskan tanda-tanda kesulitan yang teramati]\n\n**Durasi Pengamatan:**\n[Sejak kapan kesulitan ini teramati?]\n\n**Upaya yang Sudah Dilakukan:**\n[Jelaskan bantuan yang sudah diberikan]\n\n**Saran/Rekomendasi:**\n[Saran untuk penanganan lebih lanjut]",
                'description' => 'Template untuk melaporkan siswa dengan kesulitan belajar',
                'icon' => '📚',
                'is_global' => true,
            ],
            [
                'name' => 'Kecurangan Akademik',
                'category' => 'akademik',
                'title_template' => 'Laporan Kecurangan: [Jenis Ujian/Tugas]',
                'content_template' => "Melaporkan dugaan kecurangan akademik.\n\n**Detail Kejadian:**\n- Jenis kecurangan: [menyontek/plagiat/dll]\n- Tanggal: [tanggal]\n- Pada saat: [ujian/tugas/pekerjaan rumah]\n- Mata pelajaran: [mata pelajaran]\n\n**Siswa yang Terlibat:**\n[Nama dan kelas siswa yang terlibat]\n\n**Bukti/Indikasi:**\n[Jelaskan bukti atau indikasi kecurangan]\n\n**Saksi (jika ada):**\n[Nama guru/siswa yang menjadi saksi]",
                'description' => 'Template untuk melaporkan kecurangan akademik',
                'icon' => '🚨',
                'is_global' => true,
            ],

            // Sosial (Social)
            [
                'name' => 'Konflik Antar Siswa',
                'category' => 'sosial',
                'title_template' => 'Konflik Sosial: [Ringkasan Masalah]',
                'content_template' => "Melaporkan konflik sosial antar siswa yang membutuhkan mediasi.\n\n**Pihak yang Berkonflik:**\n- Pihak 1: [nama/kelompok]\n- Pihak 2: [nama/kelompok]\n\n**Asal Mula Konflik:**\n[Jelaskan bagaimana konflik bermula]\n\n**Kondisi Saat Ini:**\n[Jelaskan situasi terkini antara pihak-pihak yang berkonflik]\n\n**Dampak pada Aktivitas Sekolah:**\n[Jelaskan jika ada pengaruh ke kegiatan belajar/sekolah]\n\n**Upaya Penyelesaian yang Sudah Dicoba:**\n[Jelaskan jika sudah ada upaya mediasi]",
                'description' => 'Template untuk melaporkan konflik sosial antar siswa',
                'icon' => '🤝',
                'is_global' => true,
            ],
            [
                'name' => 'Isolasi/Pengucilan',
                'category' => 'sosial',
                'title_template' => 'Laporan Pengucilan Siswa di [Kelas/Lokasi]',
                'content_template' => "Melaporkan siswa yang mengalami isolasi atau pengucilan sosial.\n\n**Siswa yang Terisolasi:**\n- Nama: [nama]\n- Kelas: [kelas]\n\n**Gejala yang Teramati:**\n- [ ] Selalu menyendiri saat istirahat\n- [ ] Tidak memiliki teman bermain\n- [ ] Dihindari oleh teman sekelas\n- [ ] Terlihat murung/sedih\n- [ ] Lainnya: [jelaskan]\n\n**Durasi Pengamatan:**\n[Sejak kapan hal ini teramati?]\n\n**Upaya yang Sudah Dilakukan:**\n[Jelaskan jika sudah ada pendekatan]\n\n**Catatan Tambahan:**\n[Informasi lain yang mungkin membantu]",
                'description' => 'Template untuk melaporkan kasus isolasi atau pengucilan sosial',
                'icon' => '😔',
                'is_global' => true,
            ],

            // Fasilitas (Facilities)
            [
                'name' => 'Kerusakan Fasilitas',
                'category' => 'fasilitas',
                'title_template' => 'Kerusakan: [Nama Fasilitas] di [Lokasi]',
                'content_template' => "Melaporkan kerusakan fasilitas sekolah.\n\n**Detail Fasilitas:**\n- Nama fasilitas: [nama]\n- Lokasi: [gedung/ruang/lantai]\n- Kondisi: [rusak ringan/sedang/berat]\n\n**Deskripsi Kerusakan:**\n[Jelaskan kondisi kerusakan secara detail]\n\n**Penyebab (jika diketahui):**\n[Jelaskan penyebab kerusakan]\n\n**Dampak:**\n[Jelaskan dampak kerusakan pada kegiatan sekolah]\n\n**Tingkat Urgensi:**\n- [ ] Mendesak (berbahaya/mengganggu KBM)\n- [ ] Sedang (perlu diperbaiki dalam waktu dekat)\n- [ ] Rendah (bisa dijadwalkan perbaikan)",
                'description' => 'Template untuk melaporkan kerusakan fasilitas sekolah',
                'icon' => '🔧',
                'is_global' => true,
            ],

            // Keamanan (Security)
            [
                'name' => 'Kehilangan Barang',
                'category' => 'keamanan',
                'title_template' => 'Kehilangan: [Nama Barang] di [Lokasi]',
                'content_template' => "Melaporkan kehilangan barang di lingkungan sekolah.\n\n**Barang yang Hilang:**\n- Nama barang: [nama/jenis]\n- Ciri-ciri: [warna/merk/ukuran]\n- Perkiraan nilai: [jika tahu]\n\n**Detail Kehilangan:**\n- Tanggal hilang: [tanggal]\n- Terakhir dilihat: [waktu dan lokasi]\n- Pemilik: [nama pemilik]\n\n**Kronologi:**\n[Jelaskan kapan sadar barang hilang dan situasinya]\n\n**Apakah ada dugaan pencurian?**\n[Jelaskan jika ada dugaan atau kecurigaan]",
                'description' => 'Template untuk melaporkan kehilangan barang',
                'icon' => '🔍',
                'is_global' => true,
            ],
            [
                'name' => 'Orang Mencurigakan',
                'category' => 'keamanan',
                'title_template' => 'Orang Mencurigakan di [Lokasi]',
                'content_template' => "Melaporkan keberadaan orang mencurigakan di area sekolah.\n\n**Detail Sighting:**\n- Tanggal: [tanggal]\n- Waktu: [waktu]\n- Lokasi: [lokasi spesifik]\n\n**Ciri-ciri Orang:**\n- Jenis kelamin: [L/P]\n- Perkiraan usia: [usia]\n- Pakaian: [deskripsi pakaian]\n- Ciri fisik: [tinggi/berat/rambut/dll]\n\n**Perilaku yang Diamati:**\n[Jelaskan aktivitas mencurigakan yang teramati]\n\n**Tindakan yang Sudah Dilakukan:**\n[Jelaskan jika sudah melapor ke satpam/guru]",
                'description' => 'Template untuk melaporkan orang mencurigakan',
                'icon' => '👤',
                'is_global' => true,
            ],

            // Lainnya (Other)
            [
                'name' => 'Prestasi Siswa',
                'category' => 'lainnya',
                'title_template' => 'Prestasi: [Nama Siswa] - [Jenis Prestasi]',
                'content_template' => "Melaporkan prestasi siswa yang patut diapresiasi.\n\n**Informasi Siswa:**\n- Nama: [nama lengkap]\n- Kelas: [kelas]\n\n**Detail Prestasi:**\n- Jenis: [akademik/olahraga/seni/dll]\n- Nama lomba/kegiatan: [nama]\n- Penyelenggara: [nama penyelenggara]\n- Tingkat: [sekolah/kota/provinsi/nasional]\n- Peringkat: [juara/posisi]\n\n**Tanggal Pencapaian:**\n[tanggal]\n\n**Deskripsi:**\n[Ceritakan lebih detail tentang prestasi ini]\n\n**Bukti/Dokumentasi:**\n[Sertifikat/foto tersedia?]",
                'description' => 'Template untuk melaporkan prestasi positif siswa',
                'icon' => '🏆',
                'is_global' => true,
            ],
            [
                'name' => 'Saran/Masukan',
                'category' => 'lainnya',
                'title_template' => 'Saran: [Topik Saran]',
                'content_template' => "Memberikan saran atau masukan untuk sekolah.\n\n**Kategori Saran:**\n- [ ] Fasilitas\n- [ ] Kegiatan Belajar\n- [ ] Ekstrakurikuler\n- [ ] Kantin\n- [ ] Kebersihan\n- [ ] Lainnya: [sebutkan]\n\n**Saran/Masukan:**\n[Jelaskan saran Anda secara detail]\n\n**Alasan:**\n[Mengapa saran ini penting?]\n\n**Contoh/Referensi (jika ada):**\n[Berikan contoh implementasi jika ada]",
                'description' => 'Template untuk memberikan saran dan masukan',
                'icon' => '💡',
                'is_global' => true,
            ],
        ];

        foreach ($templates as $template) {
            ReportTemplate::updateOrCreate(
                ['name' => $template['name'], 'is_global' => true],
                $template
            );
        }
    }
}
