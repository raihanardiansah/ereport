<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;
use App\Models\School;
use Carbon\Carbon;

class FakeHistorySeeder extends Seeder
{
    public function run()
    {
        $school = School::first();
        if (!$school) {
            $this->command->error('No school found!');
            return;
        }

        $users = User::where('school_id', $school->id)
            ->whereIn('role', ['siswa', 'guru'])
            ->get();

        if ($users->isEmpty()) {
            $this->command->error('No users found in school!');
            return;
        }

        $categories = [
            'perilaku', 'akademik', 'kehadiran', 'bullying', 'fasilitas', 
            'kebersihan', 'kantin', 'teknologi', 'saran'
        ];

        $urgencies = ['normal', 'normal', 'normal', 'high', 'critical']; // Weighted

        // Generate data for the last 6 months (including current)
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            // Randomize number of reports per month (5 to 15)
            $count = rand(5, 15);
            
            $this->command->info("Generating {$count} reports for {$month->format('F Y')}...");

            for ($j = 0; $j < $count; $j++) {
                $user = $users->random();
                $created_at = $month->copy()->day(rand(1, 28))->hour(rand(7, 16));
                
                $urgency = $urgencies[array_rand($urgencies)];
                $status = $this->getRandomStatus($urgency);

                Report::create([
                    'school_id' => $school->id,
                    'user_id' => $user->id,
                    'title' => $this->generateTitle(),
                    'content' => $this->generateContent(),
                    'category' => $categories[array_rand($categories)],
                    'urgency' => $urgency,
                    'status' => $status,
                    'ai_classification' => $urgency === 'critical' ? 'negatif' : 'netral',
                    'is_anonymous' => rand(0, 1),
                    'created_at' => $created_at,
                    'updated_at' => $created_at,
                ]);
            }
        }
    }

    private function getRandomStatus($urgency)
    {
        if ($urgency === 'critical') return 'diproses';
        return ['selesai', 'ditindaklanjuti', 'diproses', 'dikirim'][rand(0, 3)];
    }

    private function generateTitle()
    {
        $titles = [
            'AC Kelas 10 Rusak', 'Sampah di Kantin Menumpuk', 'Bullying di Lapangan Basket',
            'Siswa Merokok di Belakang Sekolah', 'Usul Perbaikan WiFi', 'Lampu Toilet Mati',
            'Keran Air Bocor', 'Kaca Jendela Pecah', 'Buku Perpustakaan Hilang',
            'Parkiran Sepeda Berantakan', 'Wastafel Mampet', 'Proyektor Tidak Nyala'
        ];
        return $titles[array_rand($titles)] . ' ' . rand(1, 100);
    }

    private function generateContent()
    {
        return "Laporan simulasi untuk keperluan demo grafik dashboard. Ini adalah konten dummy yang dibuat otomatis oleh seeder.";
    }
}
