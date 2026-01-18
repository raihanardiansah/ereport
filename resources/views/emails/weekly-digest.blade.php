<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Mingguan</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; }
        .header { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .header p { color: rgba(255,255,255,0.8); margin: 10px 0 0; }
        .content { padding: 30px; }
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin: 20px 0; }
        .stat-card { background: #f9fafb; border-radius: 8px; padding: 15px; text-align: center; }
        .stat-number { font-size: 28px; font-weight: 700; color: #16a34a; }
        .stat-label { font-size: 12px; color: #6b7280; text-transform: uppercase; }
        .section-title { font-size: 16px; font-weight: 600; color: #374151; margin: 25px 0 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px; }
        .report-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
        .report-title { font-weight: 500; color: #374151; }
        .report-meta { font-size: 12px; color: #6b7280; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; }
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
            <h1>Ringkasan Mingguan</h1>
            <p>{{ now()->subWeek()->format('d M') }} - {{ now()->format('d M Y') }}</p>
        </div>
        <div class="content">
            <p>Halo Admin <strong>{{ $school->name }}</strong>,</p>
            <p>Berikut ringkasan aktivitas e-Report minggu ini:</p>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total_reports'] ?? 0 }}</div>
                    <div class="stat-label">Laporan Baru</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['completed_reports'] ?? 0 }}</div>
                    <div class="stat-label">Selesai Ditangani</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['pending_reports'] ?? 0 }}</div>
                    <div class="stat-label">Menunggu Tindakan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['negative_reports'] ?? 0 }}</div>
                    <div class="stat-label">Laporan Negatif</div>
                </div>
            </div>
            
            @if($recentReports->count() > 0)
            <div class="section-title">📝 Laporan Terbaru</div>
            @foreach($recentReports->take(5) as $report)
            <div class="report-item">
                <div>
                    <div class="report-title">{{ Str::limit($report->title, 40) }}</div>
                    <div class="report-meta">{{ $report->user->name }} • {{ $report->created_at->diffForHumans() }}</div>
                </div>
                <span class="badge badge-{{ $report->getClassification() ?? 'netral' }}">{{ ucfirst($report->getClassification() ?? 'Netral') }}</span>
            </div>
            @endforeach
            @endif
            
            @if(($stats['pending_reports'] ?? 0) > 0)
            <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 25px 0; border-radius: 4px;">
                <strong>⚠️ Perhatian!</strong> Ada {{ $stats['pending_reports'] }} laporan yang masih menunggu tindakan.
            </div>
            @endif
            
            <p style="text-align: center; margin-top: 25px;">
                <a href="{{ url('/dashboard') }}" class="btn">Buka Dashboard</a>
            </p>
        </div>
        <div class="footer">
            <p>Email ini dikirim setiap minggu secara otomatis.</p>
            <p>{{ $school->name }} • e-Report</p>
        </div>
    </div>
</body>
</html>
