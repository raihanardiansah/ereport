<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Baru</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; }
        .header { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .alert-box { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .report-details { background: #f9fafb; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .report-details h3 { margin-top: 0; color: #16a34a; }
        .label { font-weight: 600; color: #6b7280; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-category { background: #e5e7eb; color: #374151; }
        .badge-positif { background: #dcfce7; color: #166534; }
        .badge-negatif { background: #fee2e2; color: #991b1b; }
        .badge-netral { background: #f3f4f6; color: #6b7280; }
        .btn { display: inline-block; padding: 12px 24px; background: #16a34a; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; }
        .footer { background: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 Laporan Baru Diterima</h1>
        </div>
        <div class="content">
            <div class="alert-box">
                <strong>Perhatian!</strong> Ada laporan baru yang memerlukan perhatian Anda.
            </div>
            
            <div class="report-details">
                <h3>{{ $report->title }}</h3>
                <p><span class="label">Kategori:</span> <span class="badge badge-category">{{ ucfirst($report->category) }}</span></p>
                <p><span class="label">Pengirim:</span> {{ $report->user->name }} ({{ $report->user->getRoleDisplayName() }})</p>
                <p><span class="label">Waktu:</span> {{ $report->created_at->format('d/m/Y H:i') }}</p>
                <p><span class="label">Klasifikasi AI:</span> 
                    <span class="badge badge-{{ $report->ai_classification ?? 'netral' }}">{{ ucfirst($report->ai_classification ?? 'Netral') }}</span>
                </p>
                <p style="margin-top: 15px;"><span class="label">Isi Laporan:</span></p>
                <p style="background: #fff; padding: 15px; border-radius: 4px; border: 1px solid #e5e7eb;">{{ Str::limit($report->content, 300) }}</p>
            </div>
            
            <p style="text-align: center;">
                <a href="{{ url('/reports/' . $report->id) }}" class="btn">Lihat Detail Laporan</a>
            </p>
        </div>
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem e-Report.</p>
            <p>{{ $report->school->name ?? 'e-Report' }}</p>
        </div>
    </div>
</body>
</html>
