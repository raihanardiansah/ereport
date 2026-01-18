<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Selesai</title>
    <style>
        body { margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; color: #1f2937; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); margin-top: 20px; margin-bottom: 20px; }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 30px; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; }
        .header p { margin: 10px 0 0; opacity: 0.9; }
        .status-badge { display: inline-block; background-color: rgba(255, 255, 255, 0.2); padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: 500; margin-top: 10px; }
        .content { padding: 30px; }
        .report-card { background-color: #f9fafb; border-left: 4px solid #10b981; padding: 20px; border-radius: 4px; margin-bottom: 25px; }
        .report-title { font-weight: 600; font-size: 18px; color: #111827; margin-bottom: 5px; }
        .meta-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 25px; }
        .meta-item label { display: block; font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .meta-item value { display: block; font-weight: 500; color: #374151; }
        .timeline { margin-top: 30px; border-left: 2px solid #e5e7eb; padding-left: 20px; margin-left: 5px; }
        .timeline-item { position: relative; padding-bottom: 25px; }
        .timeline-item::before { content: ''; position: absolute; left: -26px; top: 0; width: 10px; height: 10px; border-radius: 50%; background-color: #d1d5db; border: 2px solid #fff; }
        .timeline-item.active::before { background-color: #10b981; }
        .timeline-date { font-size: 12px; color: #6b7280; margin-bottom: 2px; }
        .timeline-title { font-weight: 600; color: #374151; margin-bottom: 2px; }
        .timeline-desc { font-size: 14px; color: #4b5563; }
        .actions-list { margin-top: 20px; background-color: #ecfdf5; border-radius: 8px; padding: 20px; }
        .action-item { display: flex; align-items: flex-start; margin-bottom: 12px; }
        .action-item:last-child { margin-bottom: 0; }
        .check-icon { color: #059669; margin-right: 10px; font-size: 18px; line-height: 1.4; }
        .btn { display: block; width: 100%; text-align: center; background-color: #10b981; color: white; padding: 14px 0; border-radius: 8px; text-decoration: none; font-weight: 600; margin-top: 30px; }
        .btn:hover { background-color: #059669; }
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Laporan Selesai</h1>
            <p>Terima kasih atas partisipasi Anda</p>
            <div class="status-badge">Kasus Ditutup</div>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Halo {{ $report->user->name }},</p>
            <p>Laporan yang Anda ajukan telah selesai ditangani oleh tim kami. Berikut adalah ringkasan penyelesaian laporan tersebut.</p>

            <div class="report-card">
                <div class="report-title">{{ $report->title }}</div>
                <div style="font-size: 14px; color: #4b5563; margin-top: 5px;">
                    {{ Str::limit($report->content, 100) }}
                </div>
            </div>

            <div class="meta-grid">
                <div class="meta-item">
                    <label>Kategori</label>
                    <value>{{ ucfirst($report->category) }}</value>
                </div>
                <div class="meta-item">
                    <label>Total Durasi</label>
                    <value>{{ $totalDurationHours }} Jam</value>
                </div>
                <div class="meta-item">
                    <label>Ditangani Oleh</label>
                    <value>{{ $report->assignedTo->name ?? 'Tim Sekolah' }}</value>
                </div>
                <div class="meta-item">
                    <label>Tanggal Selesai</label>
                    <value>{{ now()->format('d M Y') }}</value>
                </div>
            </div>

            @if(count($actionsTaken) > 0)
                <h3 style="margin-bottom: 15px; font-size: 16px; color: #111827;">Tindakan yang Telah Diambil:</h3>
                <div class="actions-list">
                    @foreach($actionsTaken as $action)
                        <div class="action-item">
                            <span class="check-icon">✓</span>
                            <div style="font-size: 14px; color: #065f46;">{{ $action }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="background-color: #f3f4f6; border-radius: 8px; padding: 15px; text-align: center; color: #6b7280; font-size: 14px;">
                    Laporan telah ditandai selesai tanpa catatan tindakan spesifik.
                </div>
            @endif

            <a href="{{ route('reports.show', $report) }}" class="btn">Lihat Detail Laporan</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
