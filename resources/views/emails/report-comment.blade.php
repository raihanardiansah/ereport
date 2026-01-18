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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .comment-box {
            background: #e7f5ff;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 3px solid #0d6efd;
        }
        .label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .value {
            color: #333;
            margin-bottom: 15px;
        }
        .comment-meta {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }
        .comment-content {
            font-size: 14px;
            color: #333;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
            font-weight: 600;
        }
        .btn:hover {
            background: #218838;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Komentar Baru</h1>
    </div>
    
    <div class="content">
        <p>Ada komentar baru pada laporan Anda:</p>
        
        <div class="card">
            <div class="label">Judul Laporan</div>
            <div class="value"><strong>{{ $report->title }}</strong></div>
            
            <div class="label">Status Laporan</div>
            <div class="value">
                @if($report->status === 'selesai')
                    <span style="color: #28a745; font-weight: 600;">✓ Selesai</span>
                @elseif($report->status === 'ditindaklanjuti')
                    <span style="color: #0d6efd; font-weight: 600;">⚙️ Ditindaklanjuti</span>
                @elseif($report->status === 'diproses')
                    <span style="color: #ffc107; font-weight: 600;">⏳ Diproses</span>
                @else
                    <span style="color: #6c757d; font-weight: 600;">📨 Dikirim</span>
                @endif
            </div>
        </div>
        
        <div class="comment-box">
            <div class="comment-meta">
                <strong>{{ $comment->user->name }}</strong> 
                ({{ $comment->user->getRoleDisplayName() }}) • 
                {{ $comment->created_at->format('d M Y, H:i') }} WIB
            </div>
            <div class="comment-content">
                {{ $comment->content }}
            </div>
        </div>
        
        <center>
            <a href="{{ route('reports.show', $report) }}" class="btn">
                Lihat Laporan Lengkap
            </a>
        </center>
        
        <p style="margin-top: 30px; color: #666; font-size: 14px;">
            Anda menerima email ini karena ada aktivitas baru pada laporan yang Anda buat atau tangani.
        </p>
    </div>
    
    <div class="footer">
        <p>Email otomatis dari e-Report System<br>
        {{ $report->school->name }}</p>
    </div>
</body>
</html>
