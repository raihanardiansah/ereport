# 🚀 New Features Implementation Guide

## Status Implementasi

### ✅ Selesai
1. **Anonymous Report Limit (2x per hari)** 
2. **Audit Trail untuk Laporan Sensitif**
3. **Auto-assignment berdasarkan Kategori**

### 🔄 Dalam Progress
4. Email Summary untuk Pelapor (Report Closed)
5. Duplicate Report Detection
6. Workload Balancing Dashboard
7. Export ke PDF dengan Watermark
8. Leaderboard untuk Pelapor Positif
9. Quick Report via QR Code
10. Mobile App (PWA)

---

## 1. Anonymous Report Verification (Max 2x) ✅

### Perubahan:
- **File Modified:**
  - `app/Models/AnonymousReportLimit.php` (line 26, 38, 66)
  - `app/Http/Controllers/ReportController.php` (line 199)

### Cara Kerja:
- Setiap device fingerprint hanya bisa submit **2 laporan anonim per hari**
- Counter direset setiap hari (00:00)
- Tracking berdasarkan `device_fingerprint` + `IP address`

### Testing:
```php
// Check remaining count
$remaining = AnonymousReportLimit::getRemainingCount($fingerprint);
// Returns: 2, 1, or 0
```

---

## 2. Audit Trail untuk Laporan Sensitif ✅

### File Baru:
- **Migration:** `database/migrations/2026_01_18_080000_create_report_audit_logs_table.php`
- **Model:** `app/Models/ReportAuditLog.php`
- **Middleware:** `app/Http/Middleware/AuditReportAccess.php`

### Cara Kerja:
- **Otomatis log** setiap aksi pada laporan **Critical** atau **High**
- Actions yang di-track:
  - `created` - Saat laporan dibuat
  - `viewed` - Saat laporan dilihat (via middleware)
  - `status_changed` - Saat status diubah
  - `commented` - Saat ada komentar
  - `assigned` - Saat ditugaskan
  - `exported` - Saat di-export (akan diimplementasi)

### Data yang Disimpan:
```php
[
    'report_id' => 123,
    'user_id' => 45,
    'action' => 'viewed',
    'description' => 'User viewed the report details',
    'metadata' => ['old_status' => 'dikirim', 'new_status' => 'diproses'],
    'ip_address' => '192.168.1.1',
    'user_agent' => 'Mozilla/5.0...',
    'created_at' => '2026-01-18 08:00:00'
]
```

### Aktivasi Middleware:
Tambahkan ke `app/Http/Kernel.php` atau `bootstrap/app.php`:
```php
Route::middleware(['auth', 'audit.report'])->group(function () {
    Route::get('/reports/{report}', [ReportController::class, 'show']);
});
```

### View Audit Logs (untuk Admin):
```php
// Get all audit logs for a report
$logs = ReportAuditLog::where('report_id', $reportId)
    ->with('user')
    ->latest()
    ->get();

// Get logs by action
$viewLogs = ReportAuditLog::where('report_id', $reportId)
    ->where('action', 'viewed')
    ->get();
```

---

## 3. Auto-assignment berdasarkan Kategori ✅

### File Baru:
- **Migration:** `database/migrations/2026_01_18_080100_create_category_assignments_table.php`
- **Model:** `app/Models/CategoryAssignment.php`

### Cara Kerja:
1. Admin set rule: **Kategori X → User Y**
2. Saat laporan baru dengan kategori X masuk → Otomatis assigned ke User Y
3. User Y dapat notifikasi + email

### Setup Auto-assignment (untuk Admin):
```php
// Set assignment
CategoryAssignment::setAssignment(
    schoolId: 1,
    category: 'bullying',
    userId: 45  // Staf BK
);

CategoryAssignment::setAssignment(
    schoolId: 1,
    category: 'kesehatan',
    userId: 67  // Staf UKS
);

// Disable assignment
CategoryAssignment::setAssignment(
    schoolId: 1,
    category: 'akademik',
    userId: null  // No auto-assignment
);
```

### UI yang Perlu Dibuat:
**Page:** `/dashboard/settings/auto-assignment`

**Form:**
```
Kategori: [Dropdown: bullying, kesehatan, akademik, ...]
Assigned to: [Dropdown: Daftar Staf]
[Save Button]
```

**Table:**
| Kategori | Assigned To | Status | Actions |
|----------|-------------|--------|---------|
| Bullying | Pak Budi (BK) | Active | Edit \| Delete |
| Kesehatan | Bu Siti (UKS) | Active | Edit \| Delete |

---

## 4. Email Summary untuk Pelapor (Report Closed) 🔄

### Yang Perlu Dibuat:
1. **Mailable:** `app/Mail/ReportClosedSummaryMail.php`
2. **Template:** `resources/views/emails/report-closed-summary.blade.php`
3. **Logic:** Trigger saat status → `selesai`

### Isi Email:
- Timeline (Created → Assigned → In Progress → Closed)
- Total waktu penanganan
- Ringkasan tindakan (dari komentar type: `action_taken`)
- Link feedback (optional)

---

## 5. Duplicate Report Detection 🔄

### Teknologi:
- **AI Semantic Search** (Google Gemini Embeddings)
- **Similarity Threshold:** 85%

### Algoritma:
1. User submit laporan
2. Generate embedding dari content
3. Compare dengan laporan 7 hari terakhir
4. Jika similarity > 85% → Show warning
5. User bisa pilih: "Lanjutkan" atau "Lihat Laporan Serupa"

### File yang Perlu Dibuat:
- `app/Services/DuplicateDetectionService.php`
- Migration untuk `report_embeddings` table

---

## 6. Workload Balancing Dashboard 🔄

### Metrics:
- **Assigned Reports per Staff**
- **Average Resolution Time**
- **Workload Score** = (Pending × Urgency Weight)
  - Critical = 3 points
  - High = 2 points
  - Normal = 1 point

### UI Components:
- Bar chart: Jumlah laporan per staf
- Table: Staf dengan workload tertinggi
- Recommendation: "Assign next report to [User X]"

---

## 7. Export PDF dengan Watermark 🔄

### Features:
- Watermark: "CONFIDENTIAL - [School Name]"
- Footer: "Exported by [User Name] on [Date Time]"
- QR Code untuk verifikasi autentisitas

### Implementation:
```php
use Barryvdh\DomPDF\Facade\Pdf;

$pdf = Pdf::loadView('reports.pdf', compact('report'));
$pdf->setOption('watermark', 'CONFIDENTIAL');
return $pdf->download("report-{$report->id}.pdf");
```

---

## 8. Leaderboard untuk Pelapor Positif 🔄

### Sistem Poin:
- Laporan Positif (prestasi, kebaikan): **+10 poin**
- Laporan Netral: **+5 poin**
- Laporan Negatif: **+2 poin** (tetap dihargai karena berani lapor)

### Badges:
- 🥉 Bronze Reporter: 50 poin
- 🥈 Silver Reporter: 150 poin
- 🥇 Gold Reporter: 300 poin
- 💎 Diamond Reporter: 500 poin

### Leaderboard Page:
- Top 10 Reporters (Monthly)
- Top 10 Reporters (All Time)
- My Ranking

---

## 9. Quick Report via QR Code 🔄

### File Baru:
- **Migration:** `database/migrations/2026_01_18_080200_create_qr_codes_table.php` ✅
- **Model:** `app/Models/QrCode.php` ✅
- **Controller:** `app/Http/Controllers/QrReportController.php`

### Flow:
1. Admin generate QR code untuk lokasi (Kelas 10A, Kantin, Lab)
2. QR code di-print dan ditempel
3. Siswa scan QR → Redirect ke form laporan singkat
4. Lokasi auto-filled dari QR metadata
5. Submit → Laporan masuk dengan tag lokasi

### Generate QR Code:
```php
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrGenerator;

$qrCode = QrCode::generate(
    schoolId: 1,
    name: 'Kelas 10A',
    type: 'classroom',
    location: 'Gedung A Lantai 2',
    metadata: ['class_id' => 10, 'grade' => 10]
);

// Generate QR image
$qrImage = QrGenerator::size(300)->generate($qrCode->url);
```

---

## 10. Mobile App (PWA) 🔄

### Langkah:
1. **Manifest File:** `public/manifest.json`
2. **Service Worker:** `public/sw.js`
3. **Icons:** `public/icons/` (192x192, 512x512)
4. **Meta Tags:** Add to `<head>`

### Features PWA:
- ✅ Install to home screen
- ✅ Offline capability (cache static assets)
- ✅ Push notifications (via Firebase)
- ✅ App-like experience

### manifest.json:
```json
{
  "name": "e-Report System",
  "short_name": "e-Report",
  "start_url": "/dashboard",
  "display": "standalone",
  "background_color": "#ffffff",
  "theme_color": "#667eea",
  "icons": [
    {
      "src": "/icons/icon-192.png",
      "sizes": "192x192",
      "type": "image/png"
    },
    {
      "src": "/icons/icon-512.png",
      "sizes": "512x512",
      "type": "image/png"
    }
  ]
}
```

---

## Migration Commands

```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh migration (WARNING: Deletes all data)
php artisan migrate:fresh
```

---

## Testing Checklist

### Audit Trail:
- [ ] View critical report → Check audit log created
- [ ] Change status → Check audit log
- [ ] View audit logs di admin panel

### Auto-assignment:
- [ ] Set rule: Bullying → Pak Budi
- [ ] Submit laporan bullying
- [ ] Verify auto-assigned to Pak Budi
- [ ] Check email notification received

### Anonymous Limit:
- [ ] Submit 1st anonymous report → Success
- [ ] Submit 2nd anonymous report → Success
- [ ] Submit 3rd anonymous report → Error: "Batas maksimal 2 laporan"

---

**Status:** 3/10 fitur selesai, 7 dalam progress
**Next Priority:** Email Summary + Duplicate Detection
