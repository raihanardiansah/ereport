<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; }
        .header { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .info-box { background: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .warning-box { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 4px; font-size: 13px; }
        .btn { display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; }
        .btn:hover { background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); }
        .footer { background: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
        .link-text { word-break: break-all; background: #f3f4f6; padding: 10px; border-radius: 4px; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reset Password</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $user->name }}</strong>,</p>
            
            <div class="info-box">
                Kami menerima permintaan untuk mengatur ulang password akun Anda. Klik tombol di bawah untuk melanjutkan.
            </div>
            
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ $resetUrl }}" class="btn">Reset Password Saya</a>
            </p>
            
            <div class="warning-box">
                <strong>⚠️ Penting:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    <li>Link ini hanya berlaku selama <strong>60 menit</strong></li>
                    <li>Jika Anda tidak meminta reset password, abaikan email ini</li>
                    <li>Jangan bagikan link ini kepada siapapun</li>
                </ul>
            </div>
            
            <p style="font-size: 13px; color: #6b7280;">Jika tombol di atas tidak berfungsi, copy dan paste link berikut ke browser Anda:</p>
            <p class="link-text">{{ $resetUrl }}</p>
        </div>
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem e-Report.</p>
            <p>Jika Anda tidak melakukan permintaan ini, abaikan email ini.</p>
        </div>
    </div>
</body>
</html>
