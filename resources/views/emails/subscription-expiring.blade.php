<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Langganan Akan Berakhir</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; }
        .header { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 30px; text-align: center; }
        .header.expired { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .alert-box { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-box.danger { background: #fee2e2; border-left-color: #ef4444; }
        .days-remaining { text-align: center; margin: 30px 0; }
        .days-number { font-size: 48px; font-weight: 700; color: #f59e0b; }
        .days-number.expired { color: #ef4444; }
        .days-label { font-size: 14px; color: #6b7280; }
        .subscription-details { background: #f9fafb; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .label { font-weight: 600; color: #6b7280; }
        .btn { display: inline-block; padding: 12px 24px; background: #16a34a; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; }
        .footer { background: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header {{ $daysRemaining <= 0 ? 'expired' : '' }}">
            <h1>{{ $daysRemaining <= 0 ? '⚠️ Langganan Telah Berakhir' : '⏰ Langganan Akan Berakhir' }}</h1>
        </div>
        <div class="content">
            @if($daysRemaining <= 0)
            <div class="alert-box danger">
                <strong>Perhatian!</strong> Langganan e-Report Anda telah berakhir. Segera perpanjang untuk melanjutkan akses penuh.
            </div>
            @else
            <div class="alert-box">
                <strong>Pengingat!</strong> Langganan e-Report Anda akan segera berakhir. Perpanjang sekarang untuk menghindari gangguan layanan.
            </div>
            @endif
            
            <div class="days-remaining">
                <div class="days-number {{ $daysRemaining <= 0 ? 'expired' : '' }}">
                    {{ $daysRemaining <= 0 ? '0' : $daysRemaining }}
                </div>
                <div class="days-label">Hari Tersisa</div>
            </div>
            
            <div class="subscription-details">
                <h3 style="margin-top: 0; color: #f59e0b;">Detail Langganan</h3>
                <p><span class="label">Sekolah:</span> {{ $subscription->school->name }}</p>
                <p><span class="label">Paket:</span> {{ $subscription->package->name ?? 'Trial' }}</p>
                <p><span class="label">Berakhir Pada:</span> {{ $subscription->end_date->format('d/m/Y') }}</p>
            </div>
            
            <div style="background: #f0fdf4; border-radius: 8px; padding: 20px; margin: 20px 0;">
                <h4 style="margin-top: 0; color: #166534;">💡 Keuntungan Perpanjangan:</h4>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Akses penuh ke semua fitur</li>
                    <li>Data dan laporan tetap aman</li>
                    <li>Dukungan teknis prioritas</li>
                    <li>Update fitur terbaru</li>
                </ul>
            </div>
            
            <p style="text-align: center;">
                <a href="{{ url('/subscriptions/packages') }}" class="btn">Perpanjang Sekarang</a>
            </p>
        </div>
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem e-Report.</p>
            <p>Hubungi support jika Anda memerlukan bantuan.</p>
        </div>
    </div>
</body>
</html>
