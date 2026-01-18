<?php

namespace App\Http\Controllers;

use App\Models\CategoryAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryAssignmentController extends Controller
{
    /**
     * Display auto-assignment settings.
     */
    public function index()
    {
        $user = auth()->user();

        // Only admin_sekolah can manage auto-assignments
        if (!$user->hasRole('admin_sekolah') && !$user->isSuperAdmin()) {
            abort(403);
        }

        $assignments = CategoryAssignment::where('school_id', $user->school_id)
            ->with('assignedUser')
            ->orderBy('category')
            ->get();

        // Get available staff for assignment
        $staff = User::where('school_id', $user->school_id)
            ->whereIn('role', ['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan'])
            ->orderBy('name')
            ->get();

        // All available categories
        $categories = [
            'perilaku' => 'Perilaku',
            'akademik' => 'Akademik',
            'kehadiran' => 'Kehadiran',
            'bullying' => 'Bullying',
            'konseling' => 'Konseling',
            'kesehatan' => 'Kesehatan',
            'fasilitas' => 'Fasilitas',
            'prestasi' => 'Prestasi',
            'keamanan' => 'Keamanan',
            'ekstrakurikuler' => 'Ekstrakurikuler',
            'sosial' => 'Sosial',
            'keuangan' => 'Keuangan',
            'kebersihan' => 'Kebersihan',
            'kantin' => 'Kantin',
            'transportasi' => 'Transportasi',
            'teknologi' => 'Teknologi',
            'guru' => 'Guru',
            'kurikulum' => 'Kurikulum',
            'perpustakaan' => 'Perpustakaan',
            'laboratorium' => 'Laboratorium',
            'olahraga' => 'Olahraga',
            'keagamaan' => 'Keagamaan',
            'saran' => 'Saran',
            'lainnya' => 'Lainnya',
        ];

        return view('settings.auto-assignment', compact('assignments', 'staff', 'categories'));
    }

    /**
     * Store or update auto-assignment.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->hasRole('admin_sekolah') && !$user->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'category' => [
                'required',
                Rule::in([
                    'perilaku', 'akademik', 'kehadiran', 'bullying', 'konseling',
                    'kesehatan', 'fasilitas', 'prestasi', 'keamanan', 'ekstrakurikuler',
                    'sosial', 'keuangan', 'kebersihan', 'kantin', 'transportasi',
                    'teknologi', 'guru', 'kurikulum', 'perpustakaan', 'laboratorium',
                    'olahraga', 'keagamaan', 'saran', 'lainnya'
                ]),
            ],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
        ]);

        // Verify assigned user belongs to same school
        if ($validated['assigned_user_id']) {
            $assignedUser = User::where('id', $validated['assigned_user_id'])
                ->where('school_id', $user->school_id)
                ->whereIn('role', ['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan'])
                ->first();

            if (!$assignedUser) {
                return back()->withErrors(['assigned_user_id' => 'User tidak valid.']);
            }
        }

        CategoryAssignment::setAssignment(
            $user->school_id,
            $validated['category'],
            $validated['assigned_user_id']
        );

        return back()->with('success', 'Pengaturan auto-assignment berhasil disimpan.');
    }

    /**
     * Delete auto-assignment.
     */
    public function destroy(CategoryAssignment $assignment)
    {
        $user = auth()->user();

        if (!$user->hasRole('admin_sekolah') && !$user->isSuperAdmin()) {
            abort(403);
        }

        if ($assignment->school_id !== $user->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $assignment->delete();

        return back()->with('success', 'Auto-assignment berhasil dihapus.');
    }
}
