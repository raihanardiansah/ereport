<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class JoinSchoolController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register-join');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'join_code' => ['required', 'string', 'exists:schools,join_code'],
            'name' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'min:8', 'max:30', 'regex:/^[a-z0-9]+$/', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'role' => ['required', Rule::in(['guru', 'siswa', 'staf_kesiswaan'])],
            'nip_nisn' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'phone' => ['nullable', 'string', 'regex:/^(\+62|62|0)?8[0-9]{8,13}$/'],
        ], [
            'join_code.exists' => 'Kode sekolah tidak ditemukan.',
            'name.regex' => 'Nama hanya boleh huruf dan spasi.',
            'username.regex' => 'Username hanya boleh huruf kecil dan angka.',
            'nip_nisn.regex' => 'NIP/NISN hanya boleh angka.',
        ]);

        $school = School::where('join_code', $validated['join_code'])->first();

        // Create user with is_approved = false
        $user = User::create([
            'school_id' => $school->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'nip_nisn' => $validated['nip_nisn'] ?? null,
            'phone' => $this->normalizePhone($validated['phone'] ?? null),
            'is_approved' => false,
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Akun Anda sedang menunggu persetujuan Admin Sekolah.');
    }

    protected function normalizePhone(?string $phone): ?string
    {
        if (empty($phone)) return null;
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        if (preg_match('/^0(\d+)$/', $phone, $matches)) return '+62' . $matches[1];
        if (preg_match('/^62(\d+)$/', $phone, $matches)) return '+62' . $matches[1];
        if (preg_match('/^8(\d+)$/', $phone, $matches)) return '+628' . $matches[1];
        return $phone;
    }
}
