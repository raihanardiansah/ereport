<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\SubscriptionPackage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed report templates first (global templates)
        $this->call(ReportTemplateSeeder::class);
        // Create Super Admin (no school)
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@e-report.id',
            'username' => 'superadmin',
            'password' => Hash::make('Password123!'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        // Create a demo school
        $school = School::create([
            'name' => 'SMA Negeri 1 Demo',
            'email' => 'admin@sman1demo.sch.id',
            'npsn' => '12345678',
            'phone' => '+6221123456',
            'address' => 'Jl. Pendidikan No. 1',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Pusat',
            'subscription_status' => 'active',
        ]);

        // Create school users
        User::create([
            'school_id' => $school->id,
            'name' => 'Admin Sekolah',
            'email' => 'admin@sman1demo.sch.id',
            'username' => 'adminsekolah',
            'password' => Hash::make('Password123!'),
            'role' => 'admin_sekolah',
            'email_verified_at' => now(),
        ]);

        User::create([
            'school_id' => $school->id,
            'name' => 'Dr. Budi Santoso',
            'email' => 'kepsek@sman1demo.sch.id',
            'username' => 'kepalasekolah',
            'password' => Hash::make('Password123!'),
            'role' => 'kepala_sekolah',
            'nip_nisn' => '198501012010011001',
            'email_verified_at' => now(),
        ]);

        User::create([
            'school_id' => $school->id,
            'name' => 'Ibu Siti Aminah',
            'email' => 'gurubk@sman1demo.sch.id',
            'username' => 'gurubkdemo',
            'password' => Hash::make('Password123!'),
            'role' => 'guru_bk',
            'nip_nisn' => '199001012015012001',
            'email_verified_at' => now(),
        ]);

        User::create([
            'school_id' => $school->id,
            'name' => 'Pak Ahmad Hidayat',
            'email' => 'guru@sman1demo.sch.id',
            'username' => 'gurudemo1',
            'password' => Hash::make('Password123!'),
            'role' => 'guru',
            'nip_nisn' => '199201012018011001',
            'email_verified_at' => now(),
        ]);

        User::create([
            'school_id' => $school->id,
            'name' => 'Andi Pratama',
            'email' => 'siswa@sman1demo.sch.id',
            'username' => 'siswademo1',
            'password' => Hash::make('Password123!'),
            'role' => 'siswa',
            'nip_nisn' => '0012345678',
            'email_verified_at' => now(),
        ]);

        // Create subscription packages
        // Trial Package (auto-assigned on registration)
        SubscriptionPackage::create([
            'name' => 'Trial',
            'slug' => 'trial',
            'description' => 'Paket percobaan gratis 7 hari',
            'price' => 0,
            'duration_days' => 7,
            'max_users' => 10,
            'max_reports_per_month' => 20,
            'features' => ['Akses Dasar', 'Email Support'],
            'is_active' => true,
            'is_trial' => true,
            'sort_order' => 0,
        ]);

        SubscriptionPackage::create([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => 'Untuk sekolah kecil',
            'price' => 99000,
            'duration_days' => 30,
            'max_users' => 25,
            'max_reports_per_month' => 50,
            'features' => ['Email Support'],
            'is_trial' => false,
            'sort_order' => 1,
        ]);

        SubscriptionPackage::create([
            'name' => 'Professional',
            'slug' => 'professional',
            'description' => 'Untuk sekolah menengah',
            'price' => 249000,
            'duration_days' => 30,
            'max_users' => 100,
            'max_reports_per_month' => 200,
            'features' => ['Priority Support', 'Export Data'],
            'is_trial' => false,
            'sort_order' => 2,
        ]);

        SubscriptionPackage::create([
            'name' => 'Enterprise',
            'slug' => 'enterprise',
            'description' => 'Untuk sekolah besar',
            'price' => 499000,
            'duration_days' => 30,
            'max_users' => 500,
            'max_reports_per_month' => 1000,
            'features' => ['24/7 Support', 'Export Data', 'Custom Branding', 'API Access'],
            'is_trial' => false,
            'sort_order' => 3,
        ]);
    }
}
