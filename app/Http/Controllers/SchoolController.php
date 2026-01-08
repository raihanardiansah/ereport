<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Subscription;
use App\Models\SubscriptionPackage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SchoolController extends Controller
{
    /**
     * Show school registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register-school');
    }

    /**
     * Handle school registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            // School info
            'school_name' => [
                'required',
                'string',
                'max:100',
            ],
            'school_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('schools', 'email'),
            ],
            'npsn' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9]+$/',
                Rule::unique('schools', 'npsn'),
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\+62|62|0)?8[0-9]{8,13}$/',
            ],
            'address' => [
                'nullable',
                'string',
                'max:500',
            ],
            'province' => [
                'nullable',
                'string',
                'max:50',
            ],
            'city' => [
                'nullable',
                'string',
                'max:50',
            ],
            
            // Admin user info
            'admin_name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'admin_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'admin_username' => [
                'required',
                'string',
                'min:8',
                'max:30',
                'regex:/^[a-z0-9]+$/',
                Rule::unique('users', 'username'),
            ],
            'admin_password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'g-recaptcha-response' => 'required',
        ], [
            'school_name.required' => 'Nama sekolah wajib diisi.',
            'school_email.unique' => 'Email sekolah sudah terdaftar.',
            'npsn.regex' => 'NPSN hanya boleh angka.',
            'npsn.unique' => 'NPSN sudah terdaftar.',
            'phone.regex' => 'Format telepon tidak valid. Contoh: 08123456789 atau +628123456789',
            'admin_name.regex' => 'Nama hanya boleh huruf dan spasi.',
            'admin_username.regex' => 'Username hanya boleh huruf kecil dan angka.',
            'admin_username.min' => 'Username minimal 8 karakter.',
            'admin_email.unique' => 'Email admin sudah terdaftar.',
            'admin_username.unique' => 'Username sudah digunakan.',
            'admin_password.mixed' => 'Password harus ada huruf besar dan kecil.',
            'admin_password.numbers' => 'Password harus ada angka.',
            'admin_password.symbols' => 'Password harus ada simbol.',
            'g-recaptcha-response.required' => 'Silakan centang reCAPTCHA.',
        ]);


        // Verify reCAPTCHA
        try {
            $recaptcha = new \ReCaptcha\ReCaptcha(config('services.recaptcha.secret_key'));
            $resp = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());
            
            if (!$resp->isSuccess()) {
                // In local development, log the error but don't block registration
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
            // In local development, continue with registration
        }

        DB::beginTransaction();
        
        try {
            // Get or create trial package
            $trialPackage = SubscriptionPackage::where('is_trial', true)->first();
            
            // Create school with 7-day trial
            $trialEndsAt = now()->addDays(7);
            $school = School::create([
                'name' => $validated['school_name'],
                'email' => $validated['school_email'],
                'npsn' => $validated['npsn'] ?? null,
                'phone' => $this->normalizePhone($validated['phone'] ?? null),
                'address' => $validated['address'] ?? null,
                'province' => $validated['province'] ?? null,
                'city' => $validated['city'] ?? null,
                'subscription_status' => 'trial',
                'trial_ends_at' => $trialEndsAt,
            ]);

            // Create trial subscription record
            if ($trialPackage) {
                Subscription::create([
                    'school_id' => $school->id,
                    'package_id' => $trialPackage->id,
                    'status' => 'active',
                    'starts_at' => now(),
                    'expires_at' => $trialEndsAt,
                    'amount_paid' => 0,
                    'payment_method' => 'trial',
                    'payment_reference' => 'TRIAL-' . strtoupper(uniqid()),
                ]);
            }

            // Create admin user
            $admin = User::create([
                'school_id' => $school->id,
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'username' => $validated['admin_username'],
                'password' => Hash::make($validated['admin_password']),
                'role' => 'admin_sekolah',
                'email_verified_at' => now(),
            ]);

            DB::commit();

            // TODO: Send confirmation email
            // Mail::to($school->email)->send(new SchoolRegistered($school, $admin));

            return redirect()->route('login')
                ->with('success', 'Pendaftaran berhasil! Silakan login dengan akun admin Anda. Masa percobaan 7 hari dimulai.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Show school profile (for admin_sekolah).
     */
    public function profile()
    {
        $school = auth()->user()->school;
        return view('school.profile', compact('school'));
    }

    /**
     * Update school profile.
     */
    public function updateProfile(Request $request)
    {
        $school = auth()->user()->school;

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('schools')->ignore($school->id)],
            'npsn' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/', Rule::unique('schools')->ignore($school->id)],
            'phone' => ['nullable', 'string', 'regex:/^(\+62|62|0)?8[0-9]{8,13}$/'],
            'address' => 'nullable|string|max:500',
            'province' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
        ], [
            'phone.regex' => 'Format telepon tidak valid. Contoh: 08123456789 atau +628123456789',
        ]);

        // Normalize phone number before saving
        if (isset($validated['phone'])) {
            $validated['phone'] = $this->normalizePhone($validated['phone']);
        }

        $school->update($validated);

        return back()->with('success', 'Profil sekolah berhasil diperbarui.');
    }

    /**
     * Convert phone number from local format (08xxx, 8xxx, 628xxx) to international format (+62xxx).
     */
    protected function normalizePhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Remove all non-digit characters except +
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // Convert various formats to +62
        if (preg_match('/^0(\d+)$/', $phone, $matches)) {
            // 08xxx -> +62xxx
            return '+62' . $matches[1];
        } elseif (preg_match('/^62(\d+)$/', $phone, $matches)) {
            // 628xxx -> +628xxx
            return '+62' . $matches[1];
        } elseif (preg_match('/^\+62(\d+)$/', $phone, $matches)) {
            // Already +62 format
            return '+62' . $matches[1];
        } elseif (preg_match('/^8(\d+)$/', $phone, $matches)) {
            // 8xxx -> +628xxx
            return '+628' . $matches[1];
        }

        // Return original if no pattern matches
        return $phone;
    }
}
