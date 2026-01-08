<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the user's profile.
     */
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'regex:/^(\+62|62|0)?8[0-9]{8,13}$/'],
        ], [
            'name.regex' => 'Nama hanya boleh huruf dan spasi.',
            'phone.regex' => 'Format nomor HP tidak valid. Contoh: 08123456789 atau +628123456789',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $this->normalizePhone($validated['phone'] ?? null);
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.mixed' => 'Password harus ada huruf besar dan kecil.',
            'password.numbers' => 'Password harus ada angka.',
            'password.symbols' => 'Password harus ada simbol.',
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Show the settings page.
     */
    public function settings()
    {
        $user = auth()->user();
        return view('profile.settings', compact('user'));
    }

    /**
     * Update user settings/preferences.
     */
    public function updateSettings(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'email_new_report' => ['nullable', 'boolean'],
            'email_status_update' => ['nullable', 'boolean'],
            'email_weekly_digest' => ['nullable', 'boolean'],
            'email_comment_notification' => ['nullable', 'boolean'],
        ]);

        $emailPreferences = [
            'new_report' => $request->boolean('email_new_report'),
            'status_update' => $request->boolean('email_status_update'),
            'weekly_digest' => $request->boolean('email_weekly_digest'),
            'comment_notification' => $request->boolean('email_comment_notification'),
        ];

        $user->email_preferences = $emailPreferences;
        $user->save();

        return back()->with('success', 'Preferensi email berhasil disimpan.');
    }

    /**
     * Convert phone number from local format (08xxx, 8xxx, 628xxx) to international format (+62xxx).
     */
    protected function normalizePhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        $phone = preg_replace('/[^0-9+]/', '', $phone);

        if (preg_match('/^0(\d+)$/', $phone, $matches)) {
            return '+62' . $matches[1];
        } elseif (preg_match('/^62(\d+)$/', $phone, $matches)) {
            return '+62' . $matches[1];
        } elseif (preg_match('/^\+62(\d+)$/', $phone, $matches)) {
            return '+62' . $matches[1];
        } elseif (preg_match('/^8(\d+)$/', $phone, $matches)) {
            return '+628' . $matches[1];
        }

        return $phone;
    }
}
