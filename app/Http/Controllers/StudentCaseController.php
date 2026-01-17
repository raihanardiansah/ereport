<?php

namespace App\Http\Controllers;

use App\Models\CaseFollowUp;
use App\Models\Report;
use App\Models\StudentCase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentCaseController extends Controller
{
    /**
     * Display a listing of student cases.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = StudentCase::with(['student', 'counselor'])
            ->where('school_id', $user->school_id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search by case number or student name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $cases = $query->latest()->paginate(10);

        // Get available reports for creating case (student reports)
        $availableReports = Report::where('school_id', $user->school_id)
            ->where('status', '!=', 'selesai')
            ->where(function ($q) {
                // Reports about students OR no accused
                $q->whereDoesntHave('accusedUsers')
                  ->orWhereDoesntHave('accusedUsers', function ($sub) {
                      $sub->whereIn('role', ['guru', 'staf_kesiswaan']);
                  });
            })
            ->whereDoesntHave('studentCases') // Not already in a case
            ->with('user')
            ->latest()
            ->get();

        return view('student-cases.index', compact('cases', 'availableReports'));
    }

    /**
     * Store a newly created case.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'critical'])],
            'report_ids' => ['nullable', 'array'],
            'report_ids.*' => ['exists:reports,id'],
        ]);

        $case = StudentCase::create([
            'school_id' => $user->school_id,
            'student_id' => null, // No longer required
            'counselor_id' => $user->id,
            'title' => $validated['title'],
            'summary' => $validated['summary'],
            'priority' => $validated['priority'],
            'status' => 'open',
        ]);

        // Link selected reports and update their status
        if (!empty($validated['report_ids'])) {
            $reports = Report::whereIn('id', $validated['report_ids'])
                ->where('school_id', $user->school_id)
                ->get();
            
            foreach ($reports as $report) {
                $case->reports()->attach($report->id);
                // Auto-update report status to 'diproses' when linked
                if ($report->status === 'dikirim') {
                    $report->update(['status' => 'diproses']);
                }
            }
        }

        return redirect()->route('student-cases.show', $case)
            ->with('success', 'Kasus baru berhasil dibuat.');
    }

    /**
     * Display the specified case with timeline.
     */
    public function show(StudentCase $studentCase)
    {
        $user = auth()->user();

        // Authorization
        if ($studentCase->school_id !== $user->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $studentCase->load(['student', 'counselor', 'reports.user', 'followUps.user']);

        // Get available reports that could be linked (not yet linked to this case)
        $unlinkedReports = Report::where('school_id', $user->school_id)
            ->whereDoesntHave('studentCases', function ($query) use ($studentCase) {
                $query->where('student_case_id', $studentCase->id);
            })
            ->latest()
            ->limit(20)
            ->get();

        return view('student-cases.show', compact('studentCase', 'unlinkedReports'));
    }

    /**
     * Update case status or details.
     */
    public function update(Request $request, StudentCase $studentCase)
    {
        $user = auth()->user();

        if ($studentCase->school_id !== $user->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['sometimes', Rule::in(['open', 'in_progress', 'resolved', 'closed'])],
            'priority' => ['sometimes', Rule::in(['low', 'medium', 'high', 'critical'])],
            'title' => ['sometimes', 'string', 'max:200'],
            'summary' => ['nullable', 'string', 'max:1000'],
        ]);

        $studentCase->update($validated);

        return back()->with('success', 'Kasus berhasil diperbarui.');
    }

    /**
     * Resolve a case with outcome.
     */
    public function resolve(Request $request, StudentCase $studentCase)
    {
        $user = auth()->user();

        if ($studentCase->school_id !== $user->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'resolution_outcome' => ['required', Rule::in(array_keys(StudentCase::OUTCOMES))],
            'resolution_notes' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $studentCase->update([
            'status' => 'resolved',
            'resolution_outcome' => $validated['resolution_outcome'],
            'resolution_notes' => $validated['resolution_notes'],
            'resolved_at' => now(),
        ]);

        // Auto-complete all linked reports and update indexes
        foreach ($studentCase->reports as $report) {
            if ($report->status !== 'selesai') {
                $report->update(['status' => 'selesai']);
                // Update accused indexes when report is completed
                $report->updateAccusedIndexes();
            }
        }

        return back()->with('success', 'Kasus berhasil ditandai selesai. Semua laporan terkait juga ditandai selesai.');
    }

    /**
     * Add a follow-up record.
     */
    public function addFollowUp(Request $request, StudentCase $studentCase)
    {
        $user = auth()->user();

        if ($studentCase->school_id !== $user->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'follow_up_date' => ['required', 'date'],
            'type' => ['required', Rule::in(array_keys(CaseFollowUp::TYPES))],
            'notes' => ['required', 'string', 'min:10', 'max:1000'],
            'action_taken' => ['nullable', 'string', 'max:500'],
            'next_steps' => ['nullable', 'string', 'max:500'],
            'next_follow_up_date' => ['nullable', 'date', 'after:follow_up_date'],
        ]);

        $studentCase->followUps()->create([
            'user_id' => $user->id,
            'follow_up_date' => $validated['follow_up_date'],
            'type' => $validated['type'],
            'notes' => $validated['notes'],
            'action_taken' => $validated['action_taken'],
            'next_steps' => $validated['next_steps'],
            'next_follow_up_date' => $validated['next_follow_up_date'],
        ]);

        // Update case status if still open
        if ($studentCase->status === 'open') {
            $studentCase->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Catatan tindak lanjut berhasil ditambahkan.');
    }

    /**
     * Link a report to a case.
     */
    public function linkReport(Request $request, StudentCase $studentCase)
    {
        $user = auth()->user();

        if ($studentCase->school_id !== $user->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'report_id' => ['required', 'exists:reports,id'],
        ]);

        // Verify report belongs to same school
        $report = Report::where('id', $validated['report_id'])
            ->where('school_id', $user->school_id)
            ->firstOrFail();

        $studentCase->reports()->syncWithoutDetaching([$report->id]);

        // Auto-update report status to 'diproses' when linked
        if ($report->status === 'dikirim') {
            $report->update(['status' => 'diproses']);
        }

        return back()->with('success', 'Laporan berhasil ditautkan ke kasus.');
    }

    /**
     * Student profile with all their cases and reports.
     */
    public function studentProfile(User $student)
    {
        $user = auth()->user();

        if ($student->school_id !== $user->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        if ($student->role !== 'siswa') {
            abort(404);
        }

        $cases = StudentCase::where('student_id', $student->id)
            ->with(['counselor', 'followUps'])
            ->latest()
            ->get();

        $reports = Report::where('user_id', $student->id)
            ->orWhere(function ($q) use ($student) {
                $q->where('school_id', $student->school_id)
                  ->whereRaw("content LIKE ?", ["%{$student->name}%"]);
            })
            ->latest()
            ->get();

        // Stats
        $stats = [
            'total_cases' => $cases->count(),
            'open_cases' => $cases->where('status', 'open')->count(),
            'resolved_cases' => $cases->whereIn('status', ['resolved', 'closed'])->count(),
            'total_reports' => $reports->count(),
            'negative_reports' => $reports->filter(fn($r) => $r->getClassification() === 'negatif')->count(),
        ];

        return view('student-cases.profile', compact('student', 'cases', 'reports', 'stats'));
    }

    /**
     * Reassign case to a different counselor.
     */
    public function reassignCase(Request $request, StudentCase $studentCase)
    {
        $user = auth()->user();

        // Only admin and manajemen can reassign
        if (!$user->hasAnyRole(['admin_sekolah', 'manajemen_sekolah']) && !$user->isSuperAdmin()) {
            abort(403);
        }

        if ($studentCase->school_id !== $user->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'counselor_id' => ['required', 'exists:users,id'],
        ]);

        // Verify new counselor is from same school and has appropriate role
        $newCounselor = \App\Models\User::where('id', $validated['counselor_id'])
            ->where('school_id', $studentCase->school_id)
            ->whereIn('role', ['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan'])
            ->first();

        if (!$newCounselor) {
            return back()->withErrors(['counselor_id' => 'Konselor tidak valid atau tidak memiliki akses.']);
        }

        $oldCounselorId = $studentCase->counselor_id;
        $studentCase->update(['counselor_id' => $validated['counselor_id']]);

        // Notify new counselor
        \App\Models\Notification::create([
            'user_id' => $validated['counselor_id'],
            'school_id' => $studentCase->school_id,
            'type' => 'case_assigned',
            'title' => 'Kasus Ditugaskan',
            'message' => "Anda ditugaskan untuk menangani kasus '{$studentCase->title}'",
            'data' => ['case_id' => $studentCase->id],
        ]);

        return back()->with('success', 'Kasus berhasil ditugaskan ulang ke ' . $newCounselor->name);
    }
}
