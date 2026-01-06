<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        // Debug: Log what we receive
        \Log::info('Login attempt', [
            'has_g-recaptcha-response' => $request->has('g-recaptcha-response'),
            'g-recaptcha-response_value' => $request->input('g-recaptcha-response'),
            'g-recaptcha-response_length' => strlen($request->input('g-recaptcha-response', '')),
        ]);
        
        $request->validate([
        'username' => [
            'required',
            'string',
            'min:8',
            'max:30',
            'regex:/^[a-z0-9]+$/', // lowercase alphanumeric only
        ],
        'password' => 'required|string|min:8',
        'g-recaptcha-response' => 'required',
    ], [
        'username.regex' => 'Username hanya boleh huruf kecil dan angka tanpa spasi.',
        'username.min' => 'Username minimal 8 karakter.',
        'username.max' => 'Username maksimal 30 karakter.',
        'password.min' => 'Password minimal 8 karakter.',
        'g-recaptcha-response.required' => 'Silakan centang reCAPTCHA.',
    ]);
    
    
    // Verify reCAPTCHA
    try {
        $recaptcha = new \ReCaptcha\ReCaptcha(config('services.recaptcha.secret_key'));
        $resp = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());
        
        if (!$resp->isSuccess()) {
            // In local development, log the error but don't block login
            if (config('app.env') === 'local') {
                \Log::warning('reCAPTCHA verification failed in local environment', [
                    'errors' => $resp->getErrorCodes()
                ]);
            } else {
                return back()->withErrors(['g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.'])->withInput();
            }
        }
    } catch (\Exception $e) {
        // Handle connection errors (timeout, network issues, etc.)
        \Log::error('reCAPTCHA verification error: ' . $e->getMessage());
        
        // In production, show a user-friendly error
        if (config('app.env') !== 'local') {
            return back()->withErrors(['g-recaptcha-response' => 'Tidak dapat memverifikasi reCAPTCHA. Silakan coba lagi.'])->withInput();
        }
        // In local development, continue with login
    }

        // Find user by username
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => ['Username tidak ditemukan.'],
            ]);
        }

        // Check if account is locked
        if ($user->isLocked()) {
            $minutes = now()->diffInMinutes($user->locked_until);
            throw ValidationException::withMessages([
                'username' => ["Akun terkunci. Coba lagi dalam {$minutes} menit."],
            ]);
        }

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            $user->incrementFailedAttempts();
            
            $remaining = 5 - $user->failed_login_attempts;
            $message = $remaining > 0 
                ? "Password salah. {$remaining} percobaan tersisa."
                : "Akun terkunci selama 15 menit.";
            
            throw ValidationException::withMessages([
                'password' => [$message],
            ]);
        }

        // Check school subscription (except super_admin)
        // if (!$user->isSuperAdmin() && $user->school && !$user->school->isSubscriptionActive()) {
        //     throw ValidationException::withMessages([
        //         'username' => ['Langganan sekolah Anda telah berakhir. Hubungi admin.'],
        //     ]);
        // }

        // Success - reset failed attempts and login
        $user->resetFailedAttempts();
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended($this->getRedirectPath($user));
    }

    /**
     * Get redirect path based on user role.
     */
    protected function getRedirectPath(User $user): string
    {
        return match($user->role) {
            'super_admin' => route('dashboard.super-admin'),
            'admin_sekolah' => route('dashboard.admin-sekolah'),
            'manajemen_sekolah' => route('dashboard.manajemen-sekolah'),
            'staf_kesiswaan' => route('dashboard.staf-kesiswaan'),
            'guru' => route('dashboard.guru'),
            'siswa' => route('dashboard.siswa'),
            default => route('dashboard'),
        };
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah logout.');
    }
}
