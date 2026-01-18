<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of users in the school.
     */
    public function index(Request $request)
    {
        $query = User::where('school_id', auth()->user()->school_id)
            ->where('id', '!=', auth()->id());

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nip_nisn', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Get Pending Users separately
        $pendingUsers = User::where('school_id', auth()->user()->school_id)
            ->where('is_approved', false)
            ->latest()
            ->get();

        // Get Active Users
        $users = $query->where('is_approved', true)
            ->orderBy('name')
            ->paginate(10);
        
        return view('users.index', compact('users', 'pendingUsers'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z\s]+$/', // Letters and spaces only
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users'),
            ],
            'username' => [
                'required',
                'string',
                'min:8',
                'max:30',
                'regex:/^[a-z0-9]+$/', // Lowercase alphanumeric only
                Rule::unique('users'),
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'role' => [
                'required',
                Rule::in(['manajemen_sekolah', 'staf_kesiswaan', 'guru', 'siswa']),
            ],
            'nip_nisn' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9]+$/', // Numeric only
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\+62|62|0)?8[0-9]{8,13}$/',
            ],
        ], [
            'name.regex' => 'Nama hanya boleh huruf dan spasi.',
            'username.regex' => 'Username hanya boleh huruf kecil dan angka.',
            'username.min' => 'Username minimal 8 karakter.',
            'username.max' => 'Username maksimal 30 karakter.',
            'nip_nisn.regex' => 'NIP/NISN hanya boleh angka.',
            'nip_nisn.max' => 'NIP/NISN maksimal 20 digit.',
            'phone.regex' => 'Format nomor HP tidak valid. Contoh: 08123456789 atau +628123456789',
            'password.mixed' => 'Password harus ada huruf besar dan kecil.',
            'password.numbers' => 'Password harus ada angka.',
            'password.symbols' => 'Password harus ada simbol.',
        ]);

        User::create([
            'school_id' => auth()->user()->school_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'nip_nisn' => $validated['nip_nisn'] ?? null,
            'phone' => $this->normalizePhone($validated['phone'] ?? null),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Ensure user belongs to the same school
        if ($user->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        // Ensure user belongs to the same school
        if ($user->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'username' => [
                'required',
                'string',
                'min:8',
                'max:30',
                'regex:/^[a-z0-9]+$/',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'role' => [
                'required',
                Rule::in(['manajemen_sekolah', 'staf_kesiswaan', 'guru', 'siswa']),
            ],
            'nip_nisn' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9]+$/',
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\+62|62|0)?8[0-9]{8,13}$/',
            ],
        ], [
            'name.regex' => 'Nama hanya boleh huruf dan spasi.',
            'username.regex' => 'Username hanya boleh huruf kecil dan angka.',
            'nip_nisn.regex' => 'NIP/NISN hanya boleh angka.',
            'phone.regex' => 'Format nomor HP tidak valid. Contoh: 08123456789 atau +628123456789',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->username = $validated['username'];
        $user->role = $validated['role'];
        $user->nip_nisn = $validated['nip_nisn'] ?? null;
        $user->phone = $this->normalizePhone($validated['phone'] ?? null);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Ensure user belongs to the same school
        if ($user->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Show the import form.
     */
    public function showImport()
    {
        return view('users.import');
    }

    /**
     * Download CSV template.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_pengguna.csv"',
        ];

        $columns = ['nama', 'email', 'username', 'role', 'nip_nisn', 'telepon'];
        
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            // Add BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);
            // Example rows
            fputcsv($file, ['Budi Santoso', 'budi@email.com', 'budisantoso01', 'siswa', '0012345678', '081234567890']);
            fputcsv($file, ['Rina Kusuma', 'rina@email.com', 'rinakusuma01', 'guru', '198501012010', '089876543210']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Process CSV import.
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ], [
            'csv_file.required' => 'File CSV wajib diupload.',
            'csv_file.mimes' => 'File harus berformat CSV.',
            'csv_file.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $file = $request->file('csv_file');
        
        // Validate file upload
        if (!$file || !$file->isValid()) {
            return back()->with('error', 'File upload gagal. Silakan coba lagi.');
        }
        
        // Read file content directly into memory (avoids path issues on Windows)
        $content = file_get_contents($file->getPathname());
        
        if (empty($content)) {
            return back()->with('error', 'File CSV kosong. Silakan upload file yang berisi data.');
        }
        
        // Remove BOM if present
        $content = str_replace("\xEF\xBB\xBF", '', $content);
        
        // Split into lines
        $lines = array_filter(explode("\n", $content), function($line) {
            return !empty(trim($line));
        });
        
        if (empty($lines)) {
            return back()->with('error', 'File CSV kosong atau tidak valid.');
        }
        
        // Parse header
        $headers = str_getcsv(array_shift($lines));
        if (!$headers) {
            return back()->with('error', 'File CSV kosong atau tidak valid.');
        }
        
        // Normalize headers
        $headers = array_map(function($h) {
            return strtolower(trim($h));
        }, $headers);

        $requiredHeaders = ['nama', 'email', 'username', 'role'];
        $missingHeaders = array_diff($requiredHeaders, $headers);
        
        if (!empty($missingHeaders)) {
            return back()->with('error', 'Header tidak lengkap: ' . implode(', ', $missingHeaders));
        }

        $schoolId = auth()->user()->school_id;
        $successCount = 0;
        $errors = [];
        $rowNumber = 1;
        $validRoles = ['manajemen_sekolah', 'staf_kesiswaan', 'guru', 'siswa'];

        foreach ($lines as $line) {
            $rowNumber++;
            
            $row = str_getcsv($line);
            
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            $data = array_combine($headers, array_pad($row, count($headers), ''));
            
            // Validate required fields
            $rowErrors = [];
            
            if (empty($data['nama'])) {
                $rowErrors[] = 'Nama wajib diisi';
            } elseif (!preg_match('/^[a-zA-Z\s]+$/', $data['nama'])) {
                $rowErrors[] = 'Nama hanya boleh huruf dan spasi';
            }
            
            if (empty($data['email'])) {
                $rowErrors[] = 'Email wajib diisi';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $rowErrors[] = 'Format email tidak valid';
            } elseif (User::where('email', $data['email'])->exists()) {
                $rowErrors[] = 'Email sudah terdaftar';
            }
            
            if (empty($data['username'])) {
                $rowErrors[] = 'Username wajib diisi';
            } elseif (strlen($data['username']) < 8 || strlen($data['username']) > 30) {
                $rowErrors[] = 'Username harus 8-30 karakter';
            } elseif (!preg_match('/^[a-z0-9]+$/', $data['username'])) {
                $rowErrors[] = 'Username hanya boleh huruf kecil dan angka';
            } elseif (User::where('username', $data['username'])->exists()) {
                $rowErrors[] = 'Username sudah terdaftar';
            }
            
            if (empty($data['role'])) {
                $rowErrors[] = 'Role wajib diisi';
            } elseif (!in_array($data['role'], $validRoles)) {
                $rowErrors[] = 'Role tidak valid (pilihan: ' . implode(', ', $validRoles) . ')';
            }

            // Validate NIP/NISN if provided
            $nipNisn = $data['nip_nisn'] ?? '';
            if (!empty($nipNisn) && !preg_match('/^[0-9]+$/', $nipNisn)) {
                $rowErrors[] = 'NIP/NISN hanya boleh angka';
            }

            // Validate and normalize phone if provided
            $phone = $data['telepon'] ?? '';
            if (!empty($phone) && !preg_match('/^(\+62|62|0)?8[0-9]{8,13}$/', $phone)) {
                $rowErrors[] = 'Format telepon tidak valid. Contoh: 08123456789';
            } else {
                $phone = $this->normalizePhone($phone);
            }

            // Generate password from NIP/NISN
            if (empty($nipNisn)) {
                $rowErrors[] = 'NIP/NISN wajib untuk generate password';
            }

            if (!empty($rowErrors)) {
                $errors[] = [
                    'row' => $rowNumber,
                    'nama' => $data['nama'] ?? '-',
                    'errors' => $rowErrors,
                ];
                continue;
            }

            // Create password: Nip{nip_nisn}! (meets: uppercase, lowercase, number, symbol)
            $password = 'Nip' . $nipNisn . '!';

            // Create user
            User::create([
                'school_id' => $schoolId,
                'name' => $data['nama'],
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => Hash::make($password),
                'role' => $data['role'],
                'nip_nisn' => $nipNisn ?: null,
                'phone' => $phone ?: null,
                'email_verified_at' => now(),
            ]);

            $successCount++;
        }

        return back()->with([
            'import_success' => $successCount,
            'import_errors' => $errors,
        ]);
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

    public function approve($id)
    {
        $user = User::where('school_id', auth()->user()->school_id)->findOrFail($id);
        $user->update(['is_approved' => true]);

        // Send notification email
        try {
            \Illuminate\Support\Facades\Mail::to($user)->send(new \App\Mail\AccountApproved($user));
        } catch (\Exception $e) {
            // Log error but don't stop the process
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email approval: ' . $e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Pengguna berhasil disetujui dan email notifikasi telah dikirim.');
    }

    public function reject($id)
    {
        $user = User::where('school_id', auth()->user()->school_id)->findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Pengajuan pengguna ditolak dan dihapus.');
    }
}
