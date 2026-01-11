<?php

namespace App\Http\Controllers;

use App\Models\AnonymousReportLimit;
use App\Models\Notification;
use App\Models\Report;
use App\Models\User;
use App\Services\EmailService;
use App\Services\GamificationService;
use App\Services\SentimentAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{

    protected SentimentAnalysisService $sentimentService;

public function __construct(SentimentAnalysisService $sentimentService)
{
    $this->sentimentService = $sentimentService;
}

    
    /**
     * Display a listing of reports.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Report::with(['user', 'school', 'reportedUser']);

        // Filter by school for non-super admins
        if (!$user->isSuperAdmin()) {
            if ($user->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan'])) {
                // School staff can see school reports based on visibility rules
                $query->where('school_id', $user->school_id);
                
                // staf_kesiswaan cannot see reports about teachers (checks multi-accused)
                if ($user->role === 'staf_kesiswaan') {
                    $query->where(function ($q) {
                        // Can see: reports with no accused OR reports where all accused are students
                        $q->whereDoesntHave('accusedUsers')  // No accused = general report
                          ->orWhere(function ($sub) {
                              // Has accused but none are teachers
                              $sub->whereDoesntHave('accusedUsers', function ($accused) {
                                  $accused->whereIn('role', ['guru', 'staf_kesiswaan']);
                              });
                          });
                    });
                }
                // manajemen_sekolah and admin_sekolah can see all reports
            } else {
                // Teachers and students see only their own reports
                $query->where('user_id', $user->id);
            }
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(10);
        
        return view('reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new report.
     */
    public function create()
    {
        $user = auth()->user();
        $templates = \App\Models\ReportTemplate::forSchool($user->school_id);
        
        // Get reportable users based on the reporter's role
        // Student: can report other students, teachers, staf_kesiswaan
        // Teacher/Staf: can report other teachers, students, staf_kesiswaan
        $reportableUsers = collect();
        
        // Get all reportable users from the same school
        $reportableUsers = User::where('school_id', $user->school_id)
            ->where('id', '!=', $user->id) // Cannot report self
            ->whereIn('role', ['siswa', 'guru', 'staf_kesiswaan'])
            ->orderByRaw("FIELD(role, 'siswa', 'guru', 'staf_kesiswaan')")
            ->orderBy('name')
            ->get(['id', 'name', 'role']);
        
        return view('reports.create', compact('templates', 'reportableUsers'));
    }

    /**
     * Store a newly created report.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'title' => [
                'nullable', // AI will generate if empty
                'string',
                'max:100',
            ],
            'category' => [
                'nullable', // Now optional - AI will suggest if not provided
                Rule::in([
                    'perilaku', 'akademik', 'kehadiran', 'bullying', 'konseling',
                    'kesehatan', 'fasilitas', 'prestasi', 'keamanan', 'ekstrakurikuler',
                    'sosial', 'keuangan', 'kebersihan', 'kantin', 'transportasi',
                    'teknologi', 'guru', 'kurikulum', 'perpustakaan', 'laboratorium',
                    'olahraga', 'keagamaan', 'saran', 'lainnya'
                ]),
            ],
            'content' => [
                'required',
                'string',
                'min:20',
                'max:2000',
            ],
            'reported_user_id' => [
                'nullable',
                'exists:users,id',
            ],
            'accused_user_ids' => [
                'nullable',
                'array',
            ],
            'accused_user_ids.*' => [
                'exists:users,id',
            ],
            'attachment' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:3072', // 3MB
            ],
            'is_anonymous' => ['nullable', 'boolean'],
            'device_fingerprint' => ['nullable', 'string', 'max:64'],
        ], [
            'title.max' => 'Judul maksimal 100 karakter.',
            'content.min' => 'Isi laporan minimal 20 karakter.',
            'content.max' => 'Isi laporan maksimal 2000 karakter.',
            'attachment.mimes' => 'File harus JPG, PNG, atau PDF.',
            'attachment.max' => 'Ukuran file maksimal 3MB.',
        ]);

        // Handle anonymous reporting with rate limiting
        $isAnonymous = $request->boolean('is_anonymous');
        $deviceFingerprint = $request->input('device_fingerprint');
        
        if ($isAnonymous && $deviceFingerprint) {
            $ip = $request->ip();
            if (!AnonymousReportLimit::canSubmitAnonymous($deviceFingerprint, $ip)) {
                return back()->withErrors(['is_anonymous' => 'Anda telah mencapai batas maksimal 3 laporan anonim per hari.'])->withInput();
            }
        }

        // Verify reported user belongs to same school (if specified) - backward compatibility
        $reportedUserId = null;
        if (!empty($validated['reported_user_id'])) {
            $reportedUser = User::where('id', $validated['reported_user_id'])
                ->where('school_id', $user->school_id)
                ->first();
            if ($reportedUser) {
                $reportedUserId = $reportedUser->id;
            }
        }

        // Verify all accused users belong to same school
        $accusedUserIds = [];
        if (!empty($validated['accused_user_ids'])) {
            $accusedUserIds = User::whereIn('id', $validated['accused_user_ids'])
                ->where('school_id', $user->school_id)
                ->where('id', '!=', $user->id) // Cannot accuse self
                ->pluck('id')
                ->toArray();
        }

        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store(
                'reports/' . $user->school_id,
                'public'
            );
        }

        // Use Google Gemini AI for FULL analysis (title + sentiment + category) in ONE call
        $aiResult = $this->sentimentService->analyzeReportFull($validated['content']);
        
        // Use AI-generated title or user-provided (if any)
        $title = $validated['title'] ?? $aiResult['title'];

        // Use user-selected category or fallback to AI-suggested category
        $userCategory = $validated['category'] ?? null;
        $finalCategory = $userCategory ?? $aiResult['category'];

        $report = Report::create([
            'school_id' => $user->school_id,
            'user_id' => $isAnonymous ? null : $user->id,
            'reported_user_id' => $reportedUserId,
            'title' => $title,
            'content' => $validated['content'],
            'category' => $finalCategory,
            'attachment_path' => $attachmentPath,
            'ai_classification' => $aiResult['sentiment'],
            'ai_category' => $aiResult['category'],
            'status' => 'dikirim',
            'is_anonymous' => $isAnonymous,
            'device_fingerprint' => $isAnonymous ? $deviceFingerprint : null,
        ]);

        // Increment rate limit counter if anonymous
        if ($isAnonymous && $deviceFingerprint) {
            AnonymousReportLimit::incrementCount($deviceFingerprint, $request->ip());
        }
        

        // Attach accused users (multi-accused support)
        if (!empty($accusedUserIds)) {
            $report->accusedUsers()->sync($accusedUserIds);
        }

        // Send notifications to school admins and BK teachers
        Notification::notifyReportSubmitted($report);
        
        // Send email notifications
        EmailService::notifyReportSubmitted($report);

        // Award gamification points
        $gamification = app(GamificationService::class);
        $isFirstReport = $user->reports()->count() === 1;
        
        if ($isFirstReport) {
            $gamification->awardPoints($user, 'first_report', $report);
        }
        $gamification->awardPoints($user, 'report_submitted', $report);

        return redirect()->route('reports.show', $report)
            ->with('success', 'Laporan berhasil dikirim.');
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report)
    {
        $user = auth()->user();
        
        // Authorization check
        if (!$user->isSuperAdmin()) {
            if ($user->school_id !== $report->school_id) {
                abort(403);
            }
            // Regular users can only view their own reports
            if (!$user->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan']) && $user->id !== $report->user_id) {
                abort(403);
            }
            
            // staf_kesiswaan cannot view reports about teachers (multi-accused check)
            if ($user->role === 'staf_kesiswaan' && $report->hasTeacherAccused()) {
                abort(403, 'Anda tidak memiliki akses untuk melihat laporan tentang guru.');
            }
        }

        $report->load(['user', 'school', 'accusedUsers']);
        
        return view('reports.show', compact('report'));
    }

    /**
     * Update report status (for admins/BK).
     */
    public function updateStatus(Request $request, Report $report)
    {
        $user = auth()->user();
        
        // Only admin_sekolah, manajemen_sekolah, staf_kesiswaan can update status
        if (!$user->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan']) && !$user->isSuperAdmin()) {
            abort(403);
        }

        if ($user->school_id !== $report->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['dikirim', 'diproses', 'ditindaklanjuti', 'selesai'])],
        ]);

        $oldStatus = $report->status;
        $report->update(['status' => $validated['status']]);

        // Update accused users' indexes when report is completed (verified)
        if ($validated['status'] === 'selesai' && $oldStatus !== 'selesai') {
            $report->updateAccusedIndexes();
        }

        // Notify report creator of status change
        if ($report->user_id !== $user->id) {
            Notification::create([
                'user_id' => $report->user_id,
                'school_id' => $report->school_id,
                'type' => 'report_updated',
                'title' => 'Status Laporan Diperbarui',
                'message' => "Laporan '{$report->title}' diubah dari {$oldStatus} menjadi {$validated['status']}",
                'data' => ['report_id' => $report->id],
            ]);
            
            // Send email notification
            EmailService::notifyReportStatusChanged($report, $oldStatus, $validated['status']);
        }

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * Update classification and/or category (manual correction - human-in-the-loop).
     */
    public function updateClassification(Request $request, Report $report)
    {
        $user = auth()->user();
        
        if (!$user->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan']) && !$user->isSuperAdmin()) {
            abort(403);
        }

        if ($user->school_id !== $report->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'manual_classification' => ['nullable', Rule::in(['positif', 'negatif', 'netral'])],
            'manual_category' => ['nullable', Rule::in([
                'perilaku', 'akademik', 'kehadiran', 'bullying', 'konseling',
                'kesehatan', 'fasilitas', 'prestasi', 'keamanan', 'ekstrakurikuler',
                'sosial', 'keuangan', 'kebersihan', 'kantin', 'transportasi',
                'teknologi', 'guru', 'kurikulum', 'perpustakaan', 'laboratorium',
                'olahraga', 'keagamaan', 'saran', 'lainnya'
            ])],
        ]);

        $updates = [];
        $messages = [];

        if (!empty($validated['manual_classification'])) {
            $updates['manual_classification'] = $validated['manual_classification'];
            $messages[] = 'Klasifikasi';
        }

        if (!empty($validated['manual_category'])) {
            $updates['manual_category'] = $validated['manual_category'];
            $messages[] = 'Kategori';
        }

        if (empty($updates)) {
            return back()->with('error', 'Tidak ada perubahan yang dilakukan.');
        }

        $report->update($updates);

        return back()->with('success', implode(' dan ', $messages) . ' berhasil dikoreksi.');
    }

    /**
     * Delete a report.
     */
    public function destroy(Report $report)
    {
        $user = auth()->user();
        
        // Only creator or admin can delete
        if ($user->id !== $report->user_id && !$user->isAdminSekolah() && !$user->isSuperAdmin()) {
            abort(403);
        }

        // Only allow deletion of non-processed reports
        if (!in_array($report->status, ['dikirim'])) {
            return back()->with('error', 'Laporan yang sudah diproses tidak dapat dihapus.');
        }

        // Delete attachment if exists
        if ($report->attachment_path) {
            Storage::disk('public')->delete($report->attachment_path);
        }

        $report->delete();

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    // /**
    //  * Simulate AI classification based on content keywords.
    //  */
    // protected function simulateAIClassification(string $content): string
    // {
    //     $content = strtolower($content);
        
    //     // Keywords for negative sentiment
    //     $negativeKeywords = [
    //         'bullying', 'kekerasan', 'perkelahian', 'bolos', 'malas', 'tidak hadir',
    //         'mencontek', 'curang', 'melanggar', 'masalah', 'buruk', 'nakal',
    //         'terlambat', 'berkelahi', 'mengancam', 'intimidasi', 'merokok',
    //     ];
        
    //     // Keywords for positive sentiment
    //     $positiveKeywords = [
    //         'prestasi', 'juara', 'berprestasi', 'bagus', 'baik', 'rajin',
    //         'aktif', 'membantu', 'teladan', 'unggul', 'sukses', 'hebat',
    //         'terbaik', 'disiplin', 'sopan', 'ramah', 'kreatif',
    //     ];

    //     $negativeCount = 0;
    //     $positiveCount = 0;

    //     foreach ($negativeKeywords as $keyword) {
    //         if (str_contains($content, $keyword)) {
    //             $negativeCount++;
    //         }
    //     }

    //     foreach ($positiveKeywords as $keyword) {
    //         if (str_contains($content, $keyword)) {
    //             $positiveCount++;
    //         }
    //     }

    //     if ($negativeCount > $positiveCount) {
    //         return 'negatif';
    //     } elseif ($positiveCount > $negativeCount) {
    //         return 'positif';
    //     }

    //     return 'netral';
    // }

    /**
     * Store a new comment on a report.
     */
    public function storeComment(Request $request, Report $report)
    {
        $user = auth()->user();
        
        // Only staff can add comments
        if (!$user->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan']) && !$user->isSuperAdmin()) {
            abort(403);
        }

        // Must be same school
        if ($user->school_id !== $report->school_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => ['required', 'string', 'min:5', 'max:1000'],
            'type' => ['required', 'in:comment,follow_up,counseling_note,action_taken'],
            'is_private' => ['nullable', 'boolean'],
        ], [
            'content.required' => 'Komentar tidak boleh kosong.',
            'content.min' => 'Komentar minimal 5 karakter.',
            'content.max' => 'Komentar maksimal 1000 karakter.',
        ]);

        $report->comments()->create([
            'user_id' => $user->id,
            'content' => $validated['content'],
            'type' => $validated['type'],
            'is_private' => $request->boolean('is_private'),
        ]);

        // Notify report creator if comment is not private
        if (!$request->boolean('is_private') && $report->user_id !== $user->id) {
            Notification::create([
                'user_id' => $report->user_id,
                'school_id' => $report->school_id,
                'type' => 'report_comment',
                'title' => 'Komentar Baru pada Laporan',
                'message' => "Ada komentar baru pada laporan '{$report->title}'",
                'data' => ['report_id' => $report->id],
            ]);
        }

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}

