<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Pelapor Pertama',
                'slug' => 'first-report',
                'description' => 'Berhasil mengirim laporan pertama',
                'icon' => '🌟',
                'color' => 'gold',
                'criteria_type' => 'first_action',
                'criteria_value' => 1,
            ],
            [
                'name' => 'Aktif Melapor',
                'slug' => 'active-reporter',
                'description' => 'Mengirim 5 laporan',
                'icon' => '📝',
                'color' => 'blue',
                'criteria_type' => 'report_count',
                'criteria_value' => 5,
            ],
            [
                'name' => 'Detektif Sekolah',
                'slug' => 'school-detective',
                'description' => 'Mengirim 20 laporan',
                'icon' => '🔍',
                'color' => 'purple',
                'criteria_type' => 'report_count',
                'criteria_value' => 20,
            ],
            [
                'name' => 'Konsisten 7 Hari',
                'slug' => 'streak-7',
                'description' => 'Aktif 7 hari berturut-turut',
                'icon' => '🔥',
                'color' => 'red',
                'criteria_type' => 'consecutive_days',
                'criteria_value' => 7,
            ],
            [
                'name' => 'Konsisten 30 Hari',
                'slug' => 'streak-30',
                'description' => 'Aktif 30 hari berturut-turut',
                'icon' => '💎',
                'color' => 'gold',
                'criteria_type' => 'consecutive_days',
                'criteria_value' => 30,
            ],
            [
                'name' => 'Kolektor 100 Poin',
                'slug' => 'points-100',
                'description' => 'Mengumpulkan 100 poin',
                'icon' => '💯',
                'color' => 'green',
                'criteria_type' => 'points_threshold',
                'criteria_value' => 100,
            ],
            [
                'name' => 'Kolektor 500 Poin',
                'slug' => 'points-500',
                'description' => 'Mengumpulkan 500 poin',
                'icon' => '🏅',
                'color' => 'silver',
                'criteria_type' => 'points_threshold',
                'criteria_value' => 500,
            ],
            [
                'name' => 'Kolektor 1000 Poin',
                'slug' => 'points-1000',
                'description' => 'Mengumpulkan 1000 poin',
                'icon' => '🏆',
                'color' => 'gold',
                'criteria_type' => 'points_threshold',
                'criteria_value' => 1000,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['slug' => $badge['slug']],
                $badge
            );
        }

        $this->command->info('Created ' . count($badges) . ' badges.');
    }
}
