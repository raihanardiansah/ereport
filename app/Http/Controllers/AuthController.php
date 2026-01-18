<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

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
            'min:3', 
            'max:255',
        ],
        'password' => 'required|string|min:8',
    ], [
        'username.required' => 'Username, Email, atau No. HP wajib diisi.',
        'username.min' => 'Input minimal 3 karakter.',
        'username.max' => 'Input maksimal 255 karakter.',
        'password.min' => 'Password minimal 8 karakter.',
    ]);

        // Determine input type
        $loginValue = $request->username;
        $loginType = 'username';

        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            $loginType = 'email';
        } elseif (preg_match('/^[0-9+\s\-]+$/', $loginValue) && strlen(preg_replace('/[^0-9]/', '', $loginValue)) >= 10) {
            $loginType = 'phone';
            // Optional: Normalize phone number here if needed (e.g. remove spaces/dashes)
        }

        // Find user
        $user = User::where($loginType, $loginValue)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => ['Akun tidak ditemukan.'],
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

        return redirect('/login')->with('success', 'Anda telah logout.');
    }

    /**
     * Show forgot password form.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar dalam sistem.'])->withInput();
        }

        // Generate token
        $token = Str::random(64);

        // Delete existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Insert new token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        // Build reset URL
        $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($request->email));

        // Send email
        try {
            Mail::send('emails.password-reset', [
                'user' => $user,
                'resetUrl' => $resetUrl,
            ], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Reset Password - e-Report');
            });

            return back()->with('success', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi nanti.'])->withInput();
        }
    }

    /**
     * Show reset password form.
     */
    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link reset password tidak valid.']);
        }

        // Verify token exists and not expired
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link reset password tidak valid atau sudah kadaluarsa.']);
        }

        // Check if token is expired (60 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link reset password sudah kadaluarsa. Silakan minta link baru.']);
        }

        // Verify token matches
        if (!Hash::check($token, $record->token)) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link reset password tidak valid.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Reset password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.mixed' => 'Password harus ada huruf besar dan kecil.',
            'password.numbers' => 'Password harus ada angka.',
            'password.symbols' => 'Password harus ada simbol.',
        ]);

        // Verify token
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Link reset password tidak valid.']);
        }

        // Check token expiry
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link reset password sudah kadaluarsa.']);
        }

        // Verify token matches
        if (!Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Link reset password tidak valid.']);
        }

        // Find user and update password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User tidak ditemukan.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }
}
