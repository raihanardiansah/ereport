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
            [
                'name' => 'Laporan Bullying',
                'category' => 'perilaku',
                'title_template' => 'Laporan Insiden Bullying',
                'content_template' => "Waktu Kejadian: \nLokasi: \nPelaku (jika diketahui): \nSaksi: \n\nKronologi Kejadian:\n",
                'description' => 'Gunakan template ini untuk melaporkan tindakan perundungan atau bullying.',
                'icon' => '🚫',
                'is_active' => true,
                'is_global' => true,
            ],
            [
                'name' => 'Kerusakan Fasilitas',
                'category' => 'fasilitas',
                'title_template' => 'Laporan Kerusakan Fasilitas',
                'content_template' => "Lokasi Fasilitas: \nJenis Kerusakan: \nTingkat Kerusakan (Ringan/Sedang/Berat): \n\nDeskripsi Kerusakan:\n",
                'description' => 'Melaporkan kerusakan fasilitas sekolah seperti kursi rusak, AC mati, dll.',
                'icon' => '🔧',
                'is_active' => true,
                'is_global' => true,
            ],
            [
                'name' => 'Kehilangan Barang',
                'category' => 'keamanan',
                'title_template' => 'Laporan Kehilangan Barang',
                'content_template' => "Barang yang hilang: \nPerkiraan Waktu Hilang: \nLokasi Terakhir Dilihat: \nCiri-ciri Barang: \n\nKeterangan Tambahan:\n",
                'description' => 'Melaporkan kehilangan barang berharga di lingkungan sekolah.',
                'icon' => '🔍',
                'is_active' => true,
                'is_global' => true,
            ],
            [
                'name' => 'Pelanggaran Tata Tertib',
                'category' => 'perilaku',
                'title_template' => 'Laporan Pelanggaran Tata Tertib',
                'content_template' => "Nama Siswa (jika diketahui): \nKelas: \nJenis Pelanggaran: \nWaktu & Tempat: \n\nDetail Pelanggaran:\n",
                'description' => 'Melaporkan siswa yang melanggar aturan sekolah.',
                'icon' => '⚠️',
                'is_active' => true,
                'is_global' => true,
            ],
            [
                'name' => 'Masalah Akademik',
                'category' => 'akademik',
                'title_template' => 'Kendala Akademik',
                'content_template' => "Mata Pelajaran: \nNama Guru: \nKendala yang Dihadapi: \n\nHarapan/Saran:\n",
                'description' => 'Menyampaikan keluhan atau kendala terkait proses belajar mengajar.',
                'icon' => '📚',
                'is_active' => true,
                'is_global' => true,
            ],
            [
                'name' => 'Saran & Masukan',
                'category' => 'lainnya',
                'title_template' => 'Saran untuk Kemajuan Sekolah',
                'content_template' => "Topik: \n\nSaran/Masukan:\n\nManfaat yang Diharapkan:\n",
                'description' => 'Memberikan saran konstruktif untuk sekolah.',
                'icon' => '💡',
                'is_active' => true,
                'is_global' => true,
            ],
        ];

        foreach ($templates as $template) {
            ReportTemplate::create($template);
        }
    }
}
