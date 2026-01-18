<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Laporan Diperbarui</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; }
        .header { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .status-change { display: flex; align-items: center; justify-content: center; gap: 15px; margin: 25px 0; }
        .status-box { padding: 12px 20px; border-radius: 8px; font-weight: 600; text-align: center; }
        .status-old { background: #f3f4f6; color: #6b7280; }
        .status-new { background: #dcfce7; color: #166534; }
        .arrow { font-size: 24px; color: #16a34a; }
        .report-details { background: #f9fafb; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .label { font-weight: 600; color: #6b7280; }
        .btn { display: inline-block; padding: 12px 24px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; }
        .footer { background: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
        .status-dikirim { background: #f3f4f6; color: #6b7280; }
        .status-diproses { background: #fef3c7; color: #92400e; }
        .status-ditindaklanjuti { background: #dbeafe; color: #1e40af; }
        .status-selesai { background: #dcfce7; color: #166534; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Status Laporan Diperbarui</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $report->user->name }}</strong>,</p>
            <p>Status laporan Anda telah diperbarui:</p>
            
            <div class="status-change">
                <div class="status-box status-{{ $oldStatus }}">{{ ucfirst($oldStatus) }}</div>
                <span class="arrow">→</span>
                <div class="status-box status-{{ $newStatus }}">{{ ucfirst($newStatus) }}</div>
            </div>
            
            <div class="report-details">
                <h3 style="margin-top: 0; color: #2563eb;">{{ $report->title }}</h3>
                <p><span class="label">Kategori:</span> {{ ucfirst($report->category) }}</p>
                <p><span class="label">Waktu Kirim:</span> {{ $report->created_at->format('d/m/Y H:i') }}</p>
                <p><span class="label">Terakhir Diperbarui:</span> {{ $report->updated_at->format('d/m/Y H:i') }}</p>
            </div>
            
            @if($newStatus === 'selesai')
            <div style="background: #dcfce7; border-left: 4px solid #16a34a; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <strong>✅ Selamat!</strong> Laporan Anda telah selesai ditangani.
            </div>
            @endif
            
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
