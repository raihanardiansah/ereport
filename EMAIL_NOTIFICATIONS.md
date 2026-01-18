# Email Notification Features - Implementation Summary

## Fitur Email Notifikasi yang Telah Ditambahkan

### 1. 📋 Email Penugasan (Report Assigned)
**Kapan dikirim:** Saat admin/staf menugaskan laporan ke staf tertentu.

**Penerima:** Staf yang ditugaskan (assigned user).

**Isi Email:**
- Judul laporan
- Kategori
- Tingkat urgensi (Critical/High/Normal)
- Isi laporan (preview)
- Pelapor (dengan handling anonim)
- Tanggal laporan
- Tombol "Lihat Detail Laporan"

**File terkait:**
- `app/Mail/ReportAssignedMail.php`
- `resources/views/emails/report-assigned.blade.php`
- `app/Services/EmailService.php` → `notifyReportAssigned()`
- `app/Http/Controllers/ReportController.php` → `assignReport()` (line ~612)

---

### 2. ⚠️ Email Eskalasi Otomatis (Report Escalated)
**Kapan dikirim:** Saat laporan sudah melewati batas waktu tanpa penanganan (auto-escalation).

**Threshold:**
- Level 1: 5 jam → Notifikasi ke Staf Kesiswaan & Admin Sekolah
- Level 2: 12 jam → Notifikasi ke Manajemen Sekolah & Admin Sekolah

**Penerima:** 
- Level 1: Admin Sekolah + Staf Kesiswaan
- Level 2: Admin Sekolah + Manajemen Sekolah

**Isi Email:**
- Alert box dengan durasi pending
- Judul laporan
- Status saat ini
- Informasi penugasan (jika ada)
- Tombol "Tindak Lanjuti Sekarang"

**File terkait:**
- `app/Mail/ReportEscalatedMail.php`
- `resources/views/emails/report-escalated.blade.php`
- `app/Services/EmailService.php` → `notifyReportEscalated()`
- `app/Console/Commands/EscalateReports.php` (line ~95)

**Scheduled Job:** Berjalan otomatis setiap 15 menit via `routes/console.php`

---

### 3. 💬 Email Komentar Baru (Report Comment)
**Kapan dikirim:** Saat ada komentar baru pada laporan.

**Penerima:** 
- Pelapor (jika komentar bukan private dan bukan dari pelapor sendiri)
- Staf yang ditugaskan (jika ada dan bukan komentator)

**Isi Email:**
- Judul laporan
- Status laporan
- Nama komentator & role
- Isi komentar
- Waktu komentar
- Tombol "Lihat Laporan Lengkap"

**File terkait:**
- `app/Mail/ReportCommentMail.php`
- `resources/views/emails/report-comment.blade.php`
- `app/Services/EmailService.php` → `notifyReportComment()`
- `app/Http/Controllers/ReportController.php` → `storeComment()` (line ~565)

---

## Perubahan pada EmailService.php

### Method Baru:
1. `notifyReportAssigned(Report $report, User $assignedUser)`
2. `notifyReportEscalated(Report $report, int $hoursPending)`
3. `notifyReportComment(Report $report, ReportComment $comment)`

### Perubahan Existing:
- `notifyReportSubmitted()` → Ditambahkan 'manajemen_sekolah' ke daftar penerima (line 26)

---

## Testing Checklist

### Email Penugasan:
- [ ] Assign laporan ke staf → Cek email masuk
- [ ] Verify urgency badge (Critical/High/Normal)
- [ ] Klik tombol "Lihat Detail Laporan" → Redirect ke halaman yang benar

### Email Eskalasi:
- [ ] Buat laporan dummy
- [ ] Tunggu 5+ jam atau jalankan manual: `php artisan reports:escalate`
- [ ] Cek email masuk ke Admin & Staf Kesiswaan
- [ ] Tunggu 12+ jam → Cek email masuk ke Manajemen Sekolah

### Email Komentar:
- [ ] Tambah komentar public → Pelapor dapat email
- [ ] Tambah komentar private → Tidak ada email ke pelapor
- [ ] Tambah komentar pada laporan yang assigned → Assigned user dapat email
- [ ] Komentator tidak menerima email sendiri

---

## Environment Variables Required

Pastikan `.env` sudah dikonfigurasi dengan benar:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com
MAIL_PORT=587
MAIL_USERNAME=resend
MAIL_PASSWORD=your_resend_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Rate Limiting

**Critical Report Emails:** Menggunakan staggered delay (2 detik antar email) untuk menghindari rate limit.

**Escalation Emails:** Dikirim langsung tanpa delay (karena sudah terjadwal setiap 15 menit).

**Comment & Assignment Emails:** Dikirim langsung (frekuensi rendah, tidak perlu throttling).

---

## Logs

Semua email notification akan tercatat di `storage/logs/laravel.log`:

```
[timestamp] local.INFO: Report assigned email sent {"report_id":123,"recipient":"user@example.com"}
[timestamp] local.INFO: Report escalated emails sent {"report_id":123,"recipients_count":3,"hours_pending":6}
[timestamp] local.INFO: Report comment email sent to creator {"report_id":123,"comment_id":45,"recipient":"user@example.com"}
```

---

## Troubleshooting

### Email tidak terkirim:
1. Cek queue worker: `php artisan queue:work`
2. Cek log error di `storage/logs/laravel.log`
3. Verify MAIL_* config di `.env`
4. Test koneksi: `php artisan tinker` → `Mail::raw('Test', fn($m) => $m->to('test@example.com')->subject('Test'));`

### Email masuk spam:
1. Setup SPF, DKIM, DMARC records
2. Gunakan domain yang verified di Resend
3. Hindari kata-kata spam di subject/body

---

**Implementasi selesai!** ✅
