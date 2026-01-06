<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->string('username', 30)->unique()->after('email');
            $table->enum('role', [
                'super_admin',
                'admin_sekolah', 
                'kepala_sekolah',
                'guru_bk',
                'guru',
                'siswa'
            ])->default('siswa')->after('username');
            $table->string('nip_nisn', 20)->nullable()->after('role'); // NIP for teachers, NISN for students
            $table->string('phone', 20)->nullable()->after('nip_nisn');
            $table->integer('failed_login_attempts')->default(0)->after('remember_token');
            $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn([
                'school_id',
                'username',
                'role',
                'nip_nisn',
                'phone',
                'failed_login_attempts',
                'locked_until'
            ]);
        });
    }
};
