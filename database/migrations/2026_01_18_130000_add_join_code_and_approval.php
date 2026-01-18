<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('join_code', 10)->nullable()->unique()->after('npsn');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_approved')->default(true)->after('role');
        });

        // Generate join codes for existing schools
        $schools = DB::table('schools')->get();
        foreach ($schools as $school) {
            $code = $this->generateUniqueCode();
            DB::table('schools')->where('id', $school->id)->update(['join_code' => $code]);
        }
    }

    protected function generateUniqueCode()
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (DB::table('schools')->where('join_code', $code)->exists());
        
        return $code;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('join_code');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
    }
};
