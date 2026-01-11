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
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'], // Max 5MB
        ], [
            'name.regex' => 'Nama hanya boleh huruf dan spasi.',
            'phone.regex' => 'Format nomor HP tidak valid. Contoh: 08123456789 atau +628123456789',
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.max' => 'Ukuran foto maksimal 5MB.',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $this->normalizePhone($validated['phone'] ?? null);

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            $compressionService = new \App\Services\ImageCompressionService();
            $path = $compressionService->compressAndSaveAvatar($request->file('avatar'));

            // Delete old avatar if exists
            if ($user->avatar_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar_path);
            }

            $user->avatar_path = $path;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Delete the user's profile avatar.
     */
    public function deleteAvatar()
    {
        $user = auth()->user();

        if ($user->avatar_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar_path);
            $user->avatar_path = null;
            $user->save();

            return back()->with('success', 'Foto profil berhasil dihapus.');
        }

        return back()->with('info', 'Tidak ada foto profil untuk dihapus.');
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

    /**
     * Show active sessions for the user.
     */
    public function sessions()
    {
        $sessions = \DB::table('sessions')
            ->where('user_id', auth()->id())
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) {
                return (object) [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'last_activity' => \Carbon\Carbon::createFromTimestamp($session->last_activity),
                    'is_current' => $session->id === session()->getId(),
                    'device' => $this->parseUserAgent($session->user_agent),
                ];
            });

        return view('profile.sessions', compact('sessions'));
    }

    /**
     * Destroy a specific session.
     */
    public function destroySession(Request $request, string $sessionId)
    {
        $session = \DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$session) {
            return back()->with('error', 'Sesi tidak ditemukan.');
        }

        if ($sessionId === session()->getId()) {
            return back()->with('error', 'Tidak bisa menghapus sesi yang sedang aktif.');
        }

        \DB::table('sessions')->where('id', $sessionId)->delete();

        return back()->with('success', 'Sesi berhasil dihapus.');
    }

    /**
     * Destroy all other sessions except the current one.
     */
    public function destroyOtherSessions(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.current_password' => 'Password tidak sesuai.',
        ]);

        \DB::table('sessions')
            ->where('user_id', auth()->id())
            ->where('id', '!=', session()->getId())
            ->delete();

        return back()->with('success', 'Semua sesi lain berhasil dihapus.');
    }

    /**
     * Parse user agent to get device info.
     */
    protected function parseUserAgent(?string $userAgent): array
    {
        if (!$userAgent) {
            return ['browser' => 'Unknown', 'platform' => 'Unknown', 'device_type' => 'unknown'];
        }

        // Detect browser
        $browser = 'Unknown';
        if (preg_match('/Chrome\/[\d.]+/i', $userAgent) && !preg_match('/Edg/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Firefox\/[\d.]+/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Safari\/[\d.]+/i', $userAgent) && !preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Edg\/[\d.]+/i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/MSIE|Trident/i', $userAgent)) {
            $browser = 'Internet Explorer';
        }

        // Detect platform
        $platform = 'Unknown';
        if (preg_match('/Windows/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/Macintosh|Mac OS/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/Linux/i', $userAgent) && !preg_match('/Android/i', $userAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/Android/i', $userAgent)) {
            $platform = 'Android';
        } elseif (preg_match('/iPhone|iPad|iPod/i', $userAgent)) {
            $platform = 'iOS';
        }

        // Detect device type
        $deviceType = 'desktop';
        if (preg_match('/Mobile|Android|iPhone|iPod/i', $userAgent)) {
            $deviceType = 'mobile';
        } elseif (preg_match('/iPad|Tablet/i', $userAgent)) {
            $deviceType = 'tablet';
        }

        return [
            'browser' => $browser,
            'platform' => $platform,
            'device_type' => $deviceType,
        ];
    }
}
