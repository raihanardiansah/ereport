<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    $packages = \App\Models\SubscriptionPackage::where('is_active', true)
        ->where('is_trial', false)
        ->orderBy('sort_order')
        ->get();
    return view('landing', compact('packages'));
})->name('home');

// Quick Report QR Scan
Route::get('/q/{code}', [\App\Http\Controllers\QrCodeController::class, 'handle'])->name('qr.scan');

// Contact Form
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// Static Pages
Route::get('/privacy', [\App\Http\Controllers\PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [\App\Http\Controllers\PageController::class, 'terms'])->name('terms');
Route::get('/about', [\App\Http\Controllers\PageController::class, 'about'])->name('about');
Route::get('/blog', [\App\Http\Controllers\PageController::class, 'blog'])->name('blog');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\SchoolController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\SchoolController::class, 'register'])->name('register.store');
    
    // Password Reset Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});


Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Midtrans Webhook (no auth required - called by Midtrans)
Route::post('/webhook/midtrans', [\App\Http\Controllers\WebhookController::class, 'handle'])->name('webhook.midtrans');

// API Routes (Authenticated)
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/critical-reports/check', [\App\Http\Controllers\Api\CriticalReportController::class, 'check']);
});


// Protected Routes (Authenticated Users)
Route::middleware(['auth', 'subscription'])->group(function () {
    // Main Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Leaderboard
    Route::get('/leaderboard', [\App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard.index');

    // Profile & Settings - All authenticated users
    Route::prefix('profile')->group(function () {
        Route::get('/', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
        Route::put('/', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/avatar', [\App\Http\Controllers\ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
        Route::put('/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
        
        // Session management
        Route::get('/sessions', [\App\Http\Controllers\ProfileController::class, 'sessions'])->name('profile.sessions');
        Route::delete('/sessions/{session}', [\App\Http\Controllers\ProfileController::class, 'destroySession'])->name('profile.sessions.destroy');
        Route::delete('/sessions', [\App\Http\Controllers\ProfileController::class, 'destroyOtherSessions'])->name('profile.sessions.destroy-others');
    });
    Route::get('/settings', [\App\Http\Controllers\ProfileController::class, 'settings'])->name('settings');
    Route::put('/settings', [\App\Http\Controllers\ProfileController::class, 'updateSettings'])->name('settings.update');
    
    // Auto-assignment Settings (Admin only)
    Route::middleware(RoleMiddleware::class . ':admin_sekolah')->prefix('settings')->group(function () {
        Route::get('/auto-assignment', [\App\Http\Controllers\CategoryAssignmentController::class, 'index'])->name('settings.auto-assignment');
        Route::post('/auto-assignment', [\App\Http\Controllers\CategoryAssignmentController::class, 'store'])->name('settings.auto-assignment.store');
        Route::delete('/auto-assignment/{assignment}', [\App\Http\Controllers\CategoryAssignmentController::class, 'destroy'])->name('settings.auto-assignment.destroy');

        // QR Code Settings
        Route::get('/qr-codes', [\App\Http\Controllers\QrCodeController::class, 'index'])->name('settings.qr-codes.index');
        Route::post('/qr-codes', [\App\Http\Controllers\QrCodeController::class, 'store'])->name('settings.qr-codes.store');
        Route::get('/qr-codes/{qrCode}/print', [\App\Http\Controllers\QrCodeController::class, 'show'])->name('settings.qr-codes.show');
        Route::delete('/qr-codes/{qrCode}', [\App\Http\Controllers\QrCodeController::class, 'destroy'])->name('settings.qr-codes.destroy');
    });

    // Audit Logs (Admin & Manajemen only)
    Route::middleware(RoleMiddleware::class . ':admin_sekolah,manajemen_sekolah')->group(function () {
        Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit.index');
        Route::get('/reports/{report}/audit', [\App\Http\Controllers\AuditLogController::class, 'show'])->name('audit.show');
    });
    
    // Role-specific dashboard routes
    Route::get('/dashboard/super-admin', [DashboardController::class, 'superAdmin'])
        ->middleware(RoleMiddleware::class . ':super_admin')
        ->name('dashboard.super-admin');
    
    Route::get('/dashboard/admin-sekolah', [DashboardController::class, 'adminSekolah'])
        ->middleware(RoleMiddleware::class . ':admin_sekolah')
        ->name('dashboard.admin-sekolah');
    
    Route::get('/dashboard/manajemen-sekolah', [DashboardController::class, 'manajemenSekolah'])
        ->middleware(RoleMiddleware::class . ':manajemen_sekolah')
        ->name('dashboard.manajemen-sekolah');
    
    Route::get('/dashboard/staf-kesiswaan', [DashboardController::class, 'stafKesiswaan'])
        ->middleware(RoleMiddleware::class . ':staf_kesiswaan')
        ->name('dashboard.staf-kesiswaan');
    
    Route::get('/dashboard/guru', [DashboardController::class, 'guru'])
        ->middleware(RoleMiddleware::class . ':guru')
        ->name('dashboard.guru');
    
    Route::get('/dashboard/siswa', [DashboardController::class, 'siswa'])
        ->middleware(RoleMiddleware::class . ':siswa')
        ->name('dashboard.siswa');

    // Analytics for school admins
    Route::middleware(RoleMiddleware::class . ':admin_sekolah,manajemen_sekolah,super_admin')->group(function () {
        Route::get('/analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/export', [\App\Http\Controllers\AnalyticsController::class, 'export'])->name('analytics.export');
    });

    // Reports
    Route::resource('reports', \App\Http\Controllers\ReportController::class)
        ->except(['edit', 'update']);
    Route::patch('/reports/{report}/status', [\App\Http\Controllers\ReportController::class, 'updateStatus'])
        ->name('reports.status');
    Route::patch('/reports/{report}/classification', [\App\Http\Controllers\ReportController::class, 'updateClassification'])
        ->name('reports.classification');
    Route::post('/reports/{report}/comments', [\App\Http\Controllers\ReportController::class, 'storeComment'])
        ->name('reports.comments.store');
    Route::post('/reports/{report}/assign', [\App\Http\Controllers\ReportController::class, 'assignReport'])
        ->name('reports.assign');
    Route::delete('/reports/{report}/assign', [\App\Http\Controllers\ReportController::class, 'unassignReport'])
        ->name('reports.unassign');


    // Users Management - Admin Sekolah only
    Route::middleware(RoleMiddleware::class . ':admin_sekolah')->group(function () {
        // Import routes - must be before resource to avoid conflicts
        Route::get('/users/import', [\App\Http\Controllers\UserController::class, 'showImport'])->name('users.import');
        Route::post('/users/import', [\App\Http\Controllers\UserController::class, 'processImport'])->name('users.import.process');
        Route::get('/users/import/template', [\App\Http\Controllers\UserController::class, 'downloadTemplate'])->name('users.import.template');
        
        Route::resource('users', \App\Http\Controllers\UserController::class)
            ->except(['show']);
    });

    // Analytics - Admin & Manajemen Sekolah
    Route::middleware(RoleMiddleware::class . ':admin_sekolah,manajemen_sekolah')->group(function () {
        Route::get('/analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/export', [\App\Http\Controllers\AnalyticsController::class, 'export'])->name('analytics.export');
    });

    // PDF Export - Admin, Manajemen Sekolah, Staf Kesiswaan
    Route::middleware(RoleMiddleware::class . ':admin_sekolah,manajemen_sekolah,staf_kesiswaan')->prefix('pdf')->group(function () {
        Route::get('/student/{user}', [\App\Http\Controllers\PdfExportController::class, 'studentReportCard'])->name('pdf.student');
        Route::get('/teacher/{user}', [\App\Http\Controllers\PdfExportController::class, 'teacherReportCard'])->name('pdf.teacher');
        Route::get('/monthly', [\App\Http\Controllers\PdfExportController::class, 'monthlySummary'])->name('pdf.monthly');
        Route::get('/analytics', [\App\Http\Controllers\PdfExportController::class, 'analyticsDashboard'])->name('pdf.analytics');
    });

    // School Profile - Admin Sekolah only
    Route::middleware(RoleMiddleware::class . ':admin_sekolah')->group(function () {
        Route::get('/school/profile', [\App\Http\Controllers\SchoolController::class, 'profile'])->name('school.profile');
        Route::put('/school/profile', [\App\Http\Controllers\SchoolController::class, 'updateProfile'])->name('school.profile.update');
    });

    // Subscriptions Management - Admin Sekolah only
    Route::middleware(RoleMiddleware::class . ':admin_sekolah')->prefix('subscriptions')->group(function () {
        Route::get('/', [\App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::get('/history', [\App\Http\Controllers\SubscriptionController::class, 'paymentHistory'])->name('subscriptions.history');
        
        // Invoice routes
        Route::get('/invoice/{orderId}/view', [\App\Http\Controllers\SubscriptionController::class, 'viewInvoice'])->name('subscriptions.invoice.view');
        Route::get('/invoice/{orderId}/download', [\App\Http\Controllers\SubscriptionController::class, 'downloadInvoicePdf'])->name('subscriptions.invoice.download');
        Route::get('/invoice/{payment}', [\App\Http\Controllers\SubscriptionController::class, 'downloadInvoice'])->name('subscriptions.invoice');
        
        // Package selection and checkout (using PaymentController for Midtrans)
        Route::get('/packages', [\App\Http\Controllers\SubscriptionController::class, 'selectPackage'])->name('subscriptions.packages');
        Route::get('/checkout/{package}', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('subscriptions.checkout');
        Route::post('/checkout/{package}', [\App\Http\Controllers\PaymentController::class, 'createTransaction'])->name('subscriptions.process');
        Route::get('/waiting/{orderId}', [\App\Http\Controllers\PaymentController::class, 'waitingPayment'])->name('subscriptions.waiting');
        Route::get('/check-status/{orderId}', [\App\Http\Controllers\PaymentController::class, 'checkStatus'])->name('subscriptions.check-status');
        Route::post('/cancel/{orderId}', [\App\Http\Controllers\PaymentController::class, 'cancelTransaction'])->name('subscriptions.cancel');
        Route::post('/unsubscribe', [\App\Http\Controllers\SubscriptionController::class, 'unsubscribe'])->name('subscriptions.unsubscribe');
        
        // Promo validation
        Route::post('/validate-promo', [\App\Http\Controllers\SubscriptionController::class, 'validatePromo'])->name('subscriptions.validate-promo');
    });




    // Student Cases - Staf Kesiswaan, Manajemen, Admin
    Route::middleware(RoleMiddleware::class . ':admin_sekolah,manajemen_sekolah,staf_kesiswaan')->prefix('student-cases')->group(function () {
        Route::get('/', [\App\Http\Controllers\StudentCaseController::class, 'index'])->name('student-cases.index');
        Route::post('/', [\App\Http\Controllers\StudentCaseController::class, 'store'])->name('student-cases.store');
        Route::get('/{studentCase}', [\App\Http\Controllers\StudentCaseController::class, 'show'])->name('student-cases.show');
        Route::patch('/{studentCase}', [\App\Http\Controllers\StudentCaseController::class, 'update'])->name('student-cases.update');
        Route::post('/{studentCase}/resolve', [\App\Http\Controllers\StudentCaseController::class, 'resolve'])->name('student-cases.resolve');
        Route::post('/{studentCase}/follow-ups', [\App\Http\Controllers\StudentCaseController::class, 'addFollowUp'])->name('student-cases.follow-ups.store');
        Route::post('/{studentCase}/link-report', [\App\Http\Controllers\StudentCaseController::class, 'linkReport'])->name('student-cases.link-report');
        Route::post('/{studentCase}/reassign', [\App\Http\Controllers\StudentCaseController::class, 'reassignCase'])->name('student-cases.reassign');
        Route::get('/student/{student}/profile', [\App\Http\Controllers\StudentCaseController::class, 'studentProfile'])->name('student-cases.student-profile');
    });

    // Teacher Cases - Manajemen Sekolah and Admin only
    Route::middleware(RoleMiddleware::class . ':admin_sekolah,manajemen_sekolah')->prefix('teacher-cases')->group(function () {
        Route::get('/', [\App\Http\Controllers\TeacherCaseController::class, 'index'])->name('teacher-cases.index');
        Route::post('/', [\App\Http\Controllers\TeacherCaseController::class, 'store'])->name('teacher-cases.store');
        Route::get('/{teacherCase}', [\App\Http\Controllers\TeacherCaseController::class, 'show'])->name('teacher-cases.show');
        Route::patch('/{teacherCase}', [\App\Http\Controllers\TeacherCaseController::class, 'update'])->name('teacher-cases.update');
        Route::post('/{teacherCase}/resolve', [\App\Http\Controllers\TeacherCaseController::class, 'resolve'])->name('teacher-cases.resolve');
        Route::post('/{teacherCase}/follow-ups', [\App\Http\Controllers\TeacherCaseController::class, 'addFollowUp'])->name('teacher-cases.follow-ups.store');
        Route::post('/{teacherCase}/link-report', [\App\Http\Controllers\TeacherCaseController::class, 'linkReport'])->name('teacher-cases.link-report');
        Route::post('/{teacherCase}/reassign', [\App\Http\Controllers\TeacherCaseController::class, 'reassignCase'])->name('teacher-cases.reassign');
        Route::get('/teacher/{teacher}/profile', [\App\Http\Controllers\TeacherCaseController::class, 'teacherProfile'])->name('teacher-cases.teacher-profile');
    });

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'all'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/api/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.api');

    // Contact Support (for School Admin and other authenticated users)
    Route::get('/contact-support', [\App\Http\Controllers\ContactController::class, 'supportPage'])->name('contact.support');
    Route::post('/contact-support', [\App\Http\Controllers\ContactController::class, 'submitSupport'])->name('contact.support.submit');
    Route::post('/contact-support/reply/{message}', [\App\Http\Controllers\ContactController::class, 'storeUserReply'])->name('contact.support.reply');
    Route::get('/contact-support/api/messages', [\App\Http\Controllers\ContactController::class, 'getMessages'])->name('contact.support.api.messages');
    Route::get('/contact-support/api/thread/{message}', [\App\Http\Controllers\ContactController::class, 'getMessageThread'])->name('contact.support.api.thread');

    // Super Admin Routes
    Route::middleware(RoleMiddleware::class . ':super_admin')->prefix('admin')->group(function () {
        Route::get('/schools', [\App\Http\Controllers\AdminController::class, 'schools'])->name('admin.schools');
        Route::get('/schools/{school}', [\App\Http\Controllers\AdminController::class, 'schoolDetail'])->name('admin.school.detail');
        Route::post('/schools/{school}/toggle-status', [\App\Http\Controllers\AdminController::class, 'toggleSchoolStatus'])->name('admin.school.toggle-status');
        Route::put('/schools/{school}/subscription', [\App\Http\Controllers\AdminController::class, 'updateSchoolSubscription'])->name('admin.school.update-subscription');
        Route::get('/audit-logs', [\App\Http\Controllers\AdminController::class, 'auditLogs'])->name('admin.audit-logs');
        Route::get('/backup', [\App\Http\Controllers\AdminController::class, 'backup'])->name('admin.backup');
        
        // New superadmin features
        Route::get('/analytics', [\App\Http\Controllers\AdminController::class, 'analytics'])->name('admin.analytics');
        Route::get('/student-cases', [\App\Http\Controllers\AdminController::class, 'allStudentCases'])->name('admin.student-cases');
        Route::get('/export/csv/{type}', [\App\Http\Controllers\AdminController::class, 'exportCsv'])->name('admin.export.csv');
        Route::get('/export/pdf', [\App\Http\Controllers\AdminController::class, 'exportPdf'])->name('admin.export.pdf');
        
        // Messages Management
        Route::get('/messages', [\App\Http\Controllers\ContactController::class, 'index'])->name('admin.messages');
        Route::get('/messages/unread-count', [\App\Http\Controllers\ContactController::class, 'unreadCount'])->name('admin.messages.unread-count');
        Route::get('/messages/{message}', [\App\Http\Controllers\ContactController::class, 'show'])->name('admin.messages.show');
        Route::post('/messages/{message}/read', [\App\Http\Controllers\ContactController::class, 'markAsRead'])->name('admin.messages.read');
        Route::post('/messages/{message}/reply', [\App\Http\Controllers\ContactController::class, 'reply'])->name('admin.messages.reply');
        Route::put('/messages/{message}/status', [\App\Http\Controllers\ContactController::class, 'updateStatus'])->name('admin.messages.status');
        Route::get('/messages/{message}/replies', [\App\Http\Controllers\ContactController::class, 'getAdminReplies'])->name('admin.messages.replies');

        // Package & Promotion Management
        Route::get('/packages', [\App\Http\Controllers\Admin\PackageController::class, 'index'])->name('admin.packages');
        Route::post('/packages/sync', [\App\Http\Controllers\Admin\PackageController::class, 'syncPackages'])->name('admin.packages.sync');
        
        // Package CRUD
        Route::get('/packages/create', [\App\Http\Controllers\Admin\PackageController::class, 'createPackage'])->name('admin.packages.create');
        Route::post('/packages', [\App\Http\Controllers\Admin\PackageController::class, 'storePackage'])->name('admin.packages.store');
        Route::get('/packages/{package}/edit', [\App\Http\Controllers\Admin\PackageController::class, 'editPackage'])->name('admin.packages.edit');
        Route::put('/packages/{package}', [\App\Http\Controllers\Admin\PackageController::class, 'updatePackage'])->name('admin.packages.update');
        Route::post('/packages/{package}/toggle', [\App\Http\Controllers\Admin\PackageController::class, 'togglePackage'])->name('admin.packages.toggle');
        Route::delete('/packages/{package}', [\App\Http\Controllers\Admin\PackageController::class, 'deletePackage'])->name('admin.packages.delete');
        Route::post('/packages/reorder', [\App\Http\Controllers\Admin\PackageController::class, 'reorderPackages'])->name('admin.packages.reorder');
        
        // Promotion CRUD
        Route::get('/promotions/create', [\App\Http\Controllers\Admin\PackageController::class, 'createPromotion'])->name('admin.promotions.create');
        Route::post('/promotions', [\App\Http\Controllers\Admin\PackageController::class, 'storePromotion'])->name('admin.promotions.store');
        Route::get('/promotions/{promotion}/edit', [\App\Http\Controllers\Admin\PackageController::class, 'editPromotion'])->name('admin.promotions.edit');
        Route::put('/promotions/{promotion}', [\App\Http\Controllers\Admin\PackageController::class, 'updatePromotion'])->name('admin.promotions.update');
        Route::post('/promotions/{promotion}/toggle', [\App\Http\Controllers\Admin\PackageController::class, 'togglePromotion'])->name('admin.promotions.toggle');
        Route::delete('/promotions/{promotion}', [\App\Http\Controllers\Admin\PackageController::class, 'deletePromotion'])->name('admin.promotions.delete');
        Route::post('/promotions/validate', [\App\Http\Controllers\Admin\PackageController::class, 'validatePromoCode'])->name('admin.promotions.validate');
    });
});
