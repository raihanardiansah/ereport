<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Critical Baru</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .badge {
            display: inline-block;
            background-color: rgba(255,255,255,0.2);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            margin-top: 10px;
        }
        .content {
            padding: 30px 20px;
        }
        .alert-box {
            background-color: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-box p {
            margin: 0;
            color: #991b1b;
            font-weight: 500;
        }
        .report-details {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #6b7280;
            min-width: 120px;
        }
        .detail-value {
            color: #111827;
        }
        .excerpt {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            font-style: italic;
            color: #4b5563;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(220, 38, 38, 0.2);
        }
        .cta-button:hover {
            background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%);
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LAPORAN CRITICAL BARU</h1>
            <div class="badge">PERLU PERHATIAN SEGERA</div>
        </div>

        <div class="content">
            <div class="alert-box">
                <p>⚠️ Laporan dengan tingkat urgency CRITICAL telah dibuat dan memerlukan tindakan segera.</p>
            </div>

            <div class="report-details">
                <div class="detail-row">
                    <span class="detail-label">Judul:</span>
                    <span class="detail-value"><strong>{{ $report->title }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Kategori:</span>
                    <span class="detail-value">{{ ucfirst($report->category) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Pelapor:</span>
                    <span class="detail-value">
                        @if($report->is_anonymous)
                            Pelapor (Anonim)
                        @else
                            {{ $report->user->name ?? 'Unknown' }}
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Waktu:</span>
                    <span class="detail-value">{{ $report->created_at->format('d M Y, H:i') }} WIB</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">{{ ucfirst($report->status) }}</span>
                </div>
            </div>

            <div class="excerpt">
                <strong>Isi Laporan:</strong><br>
                {{ Str::limit(strip_tags($report->content), 200) }}
            </div>

            <center>
                <a href="{{ $reportUrl }}" class="cta-button">
                    📋 Lihat Detail Laporan
                </a>
            </center>

            <p style="color: #6b7280; font-size: 14px; margin-top: 20px;">
                Silakan segera tinjau laporan ini dan lakukan tindakan yang diperlukan. 
                Laporan critical memerlukan penanganan prioritas tinggi.
            </p>
        </div>

        <div class="footer">
            <p><strong>e-Report System</strong></p>
            <p>{{ $report->school->name ?? 'Sistem Pelaporan Sekolah' }}</p>
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
