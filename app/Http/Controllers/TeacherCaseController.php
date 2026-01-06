<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\TeacherCase;
use App\Models\CaseTeacherFollowUp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherCaseController extends Controller
{
    /**
     * Display a listing of teacher cases.
     * Access: manajemen_sekolah, admin_sekolah only
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = TeacherCase::with(['teacher', 'handler', 'school'])
            ->where('school_id', $user->school_id)
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('case_number', 'like', "%{$search}%")
                  ->orWhereHas('teacher', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $cases = $query->paginate(15);
        
        // Get available reports for creating case (teacher reports - has teacher accused)
        $availableReports = Report::where('school_id', $user->school_id)
            ->where('status', '!=', 'selesai')
            ->whereHas('accusedUsers', function ($q) {
                // Reports about teachers
                $q->whereIn('role', ['guru', 'staf_kesiswaan']);
            })
            ->whereDoesntHave('teacherCases') // Not already in a case
            ->with('user')
            ->latest()
            ->get();

        return view('teacher-cases.index', compact('cases', 'availableReports'));
    }

    /**
     * Store a newly created teacher case.
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

        $case = TeacherCase::create([
            'school_id' => $user->school_id,
            'teacher_id' => null, // No longer required
            'handler_id' => $user->id,
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

        return redirect()->route('teacher-cases.show', $case)
            ->with('success', 'Kasus guru baru berhasil dibuat.');
    }

    /**
     * Display the specified teacher case.
     */
    public function show(TeacherCase $teacherCase)
    {
        $user = auth()->user();

        if ($teacherCase->school_id !== $user->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $teacherCase->load(['teacher', 'handler', 'reports.user', 'followUps.conductor']);

        // Get unlinked reports (not yet linked to this case)
        $availableReports = Report::where('school_id', $user->school_id)
            ->whereDoesntHave('teacherCases', function ($query) use ($teacherCase) {
                $query->where('teacher_case_id', $teacherCase->id);
            })
            ->latest()
            ->limit(20)
            ->get();

        return view('teacher-cases.show', compact('teacherCase', 'availableReports'));
    }

    /**
     * Update the specified teacher case.
     */
    public function update(Request $request, TeacherCase $teacherCase)
    {
        $user = auth()->user();

        if ($teacherCase->school_id !== $user->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:200'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'priority' => ['sometimes', Rule::in(['low', 'medium', 'high', 'critical'])],
            'status' => ['sometimes', Rule::in(['open', 'in_progress', 'resolved', 'closed'])],
        ]);

        // Don't allow changing status to resolved without resolution notes
        if (isset($validated['status']) && in_array($validated['status'], ['resolved', 'closed'])) {
            if (empty($teacherCase->resolution_notes)) {
                return back()->with('error', 'Mohon lengkapi catatan penyelesaian terlebih dahulu.');
            }
        }

        $teacherCase->update($validated);

        return back()->with('success', 'Kasus berhasil diperbarui.');
    }

    /**
     * Resolve a teacher case.
     */
    public function resolve(Request $request, TeacherCase $teacherCase)
    {
        $user = auth()->user();

        if ($teacherCase->school_id !== $user->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'resolution_notes' => ['required', 'string', 'max:2000'],
            'resolution_outcome' => ['required', Rule::in(array_keys(TeacherCase::OUTCOMES))],
        ]);

        $teacherCase->update([
            'resolution_notes' => $validated['resolution_notes'],
            'resolution_outcome' => $validated['resolution_outcome'],
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        // Auto-complete all linked reports and update indexes
        foreach ($teacherCase->reports as $report) {
            if ($report->status !== 'selesai') {
                $report->update(['status' => 'selesai']);
                // Update accused indexes when report is completed
                $report->updateAccusedIndexes();
            }
        }

        return back()->with('success', 'Kasus berhasil diselesaikan. Semua laporan terkait juga ditandai selesai.');
    }

    /**
     * Add a follow-up to a teacher case.
     */
    public function addFollowUp(Request $request, TeacherCase $teacherCase)
    {
        $user = auth()->user();

        if ($teacherCase->school_id !== $user->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => ['required', Rule::in(array_keys(CaseTeacherFollowUp::TYPES))],
            'follow_up_date' => ['required', 'date'],
            'notes' => ['required', 'string', 'max:2000'],
            'outcome' => ['nullable', 'string', 'max:500'],
            'next_action' => ['nullable', 'string', 'max:200'],
            'next_follow_up_date' => ['nullable', 'date', 'after:today'],
        ]);

        $teacherCase->followUps()->create([
            'conducted_by' => $user->id,
            ...$validated,
        ]);

        // Update case status to in_progress if it was open
        if ($teacherCase->status === 'open') {
            $teacherCase->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Catatan tindak lanjut berhasil ditambahkan.');
    }

    /**
     * Link a report to a teacher case.
     */
    public function linkReport(Request $request, TeacherCase $teacherCase)
    {
        $user = auth()->user();

        if ($teacherCase->school_id !== $user->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'report_id' => ['required', 'exists:reports,id'],
        ]);

        // Verify report belongs to same school
        $report = Report::where('id', $validated['report_id'])
            ->where('school_id', $user->school_id)
            ->firstOrFail();

        $teacherCase->reports()->syncWithoutDetaching([$report->id]);

        // Auto-update report status to 'diproses' when linked
        if ($report->status === 'dikirim') {
            $report->update(['status' => 'diproses']);
        }

        return back()->with('success', 'Laporan berhasil ditautkan ke kasus.');
    }

    /**
     * Teacher profile with all their cases and reports.
     */
    public function teacherProfile(User $teacher)
    {
        $user = auth()->user();

        if ($teacher->school_id !== $user->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        // Only allow viewing profiles of teachers/staff
        if (!in_array($teacher->role, ['guru', 'staf_kesiswaan'])) {
            abort(404);
        }

        $cases = TeacherCase::where('teacher_id', $teacher->id)
            ->with(['handler', 'followUps'])
            ->latest()
            ->get();

        // Get reports where teacher is accused
        $reports = Report::whereHas('accusedUsers', function ($q) use ($teacher) {
            $q->where('users.id', $teacher->id);
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

        return view('teacher-cases.profile', compact('teacher', 'cases', 'reports', 'stats'));
    }
}
