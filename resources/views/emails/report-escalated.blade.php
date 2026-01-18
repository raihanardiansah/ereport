<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .alert-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        .label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
        }
        .value {
            color: #333;
            margin-bottom: 15px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-critical {
            background: #dc3545;
            color: white;
        }
        .badge-high {
            background: #fd7e14;
            color: white;
        }
        .badge-warning {
            background: #ffc107;
            color: #333;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
            font-weight: 600;
        }
        .btn:hover {
            background: #c82333;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }
        .time-warning {
            font-size: 18px;
            font-weight: 700;
            color: #dc3545;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Eskalasi Laporan</h1>
    </div>
    
    <div class="content">
        <div class="alert-box">
            <strong>Peringatan Eskalasi Otomatis</strong>
            <p style="margin: 10px 0 0 0;">
                Laporan berikut telah menunggu <strong>{{ $hoursPending }} jam</strong> tanpa tindak lanjut.
            </p>
        </div>
        
        <div class="time-warning">
            🕐 {{ $hoursPending }} Jam Belum Ditangani
        </div>
        
        <div class="card">
            <div class="label">Judul Laporan</div>
            <div class="value"><strong>{{ $report->title }}</strong></div>
            
            <div class="label">Kategori</div>
            <div class="value">{{ ucfirst($report->category) }}</div>
            
            <div class="label">Tingkat Urgensi</div>
            <div class="value">
                @if($report->urgency === 'critical')
                    <span class="badge badge-critical">🚨 KRITIS</span>
                @elseif($report->urgency === 'high')
                    <span class="badge badge-high">⚠️ TINGGI</span>
                @else
                    <span class="badge badge-warning">Normal</span>
                @endif
            </div>
            
            <div class="label">Status Saat Ini</div>
            <div class="value">
                <span class="badge badge-warning">{{ ucfirst($report->status) }}</span>
            </div>
            
            <div class="label">Isi Laporan</div>
            <div class="value">{{ Str::limit($report->content, 200) }}</div>
            
            <div class="label">Dilaporkan oleh</div>
            <div class="value">
                @if($report->is_anonymous)
                    Pengguna Anonim
                @else
                    {{ $report->user->name }} ({{ $report->user->getRoleDisplayName() }})
                @endif
            </div>
            
            <div class="label">Tanggal Laporan</div>
            <div class="value">{{ $report->created_at->format('d F Y, H:i') }} WIB</div>
            
            @if($report->assigned_to)
            <div class="label">Ditugaskan kepada</div>
            <div class="value">{{ $report->assignedTo->name }}</div>
            @else
            <div class="label">Status Penugasan</div>
            <div class="value"><strong style="color: #dc3545;">Belum ditugaskan</strong></div>
            @endif
        </div>
        
        <center>
            <a href="{{ route('reports.show', $report) }}" class="btn">
                Tindak Lanjuti Sekarang
            </a>
        </center>
        
        <p style="margin-top: 30px; color: #666; font-size: 14px; background: #fff3cd; padding: 15px; border-radius: 6px;">
            <strong>⚠️ Tindakan Diperlukan:</strong><br>
            Laporan ini telah melewati batas waktu respons yang diharapkan. Mohon segera ditindaklanjuti untuk memastikan penanganan yang optimal.
        </p>
    </div>
    
    <div class="footer">
        <p>Email otomatis dari e-Report System<br>
        {{ $report->school->name }}</p>
    </div>
</body>
</html>
