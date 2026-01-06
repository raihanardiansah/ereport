<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $transaction->order_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .container {
            padding: 40px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            margin: -40px -40px 30px -40px;
        }
        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .header-info {
            display: table;
            width: 100%;
        }
        .header-left, .header-right {
            display: table-cell;
            vertical-align: top;
        }
        .header-right {
            text-align: right;
        }
        .invoice-number {
            font-size: 14px;
            opacity: 0.9;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 10px;
            text-transform: uppercase;
            color: #666;
            font-weight: bold;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }
        .billing-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .billing-from, .billing-to {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .info-text {
            color: #666;
            font-size: 11px;
            margin-bottom: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background-color: #f8f9fa;
        }
        table th {
            padding: 12px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            color: #555;
            border-bottom: 2px solid #dee2e6;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
        }
        .product-name {
            font-weight: bold;
            margin-bottom: 3px;
        }
        .product-desc {
            font-size: 10px;
            color: #666;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-box {
            background-color: #f8f9fa;
            padding: 20px;
            margin-left: 50%;
            border-radius: 8px;
        }
        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .summary-label, .summary-value {
            display: table-cell;
        }
        .summary-value {
            text-align: right;
            font-weight: bold;
        }
        .total-row {
            border-top: 2px solid #dee2e6;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 14px;
        }
        .total-row .summary-value {
            color: #667eea;
            font-size: 16px;
        }
        .payment-info {
            display: table;
            width: 100%;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }
        .payment-col {
            display: table-cell;
            width: 50%;
            padding-right: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-expired {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        .footer-note {
            margin-bottom: 10px;
        }
        .footer-timestamp {
            color: #999;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-info">
                <div class="header-left">
                    <h1>INVOICE</h1>
                    <div class="invoice-number">{{ $transaction->order_id }}</div>
                </div>
                <div class="header-right">
                    <div style="font-size: 11px; opacity: 0.8; margin-bottom: 5px;">Tanggal</div>
                    <div style="font-size: 14px; font-weight: bold;">{{ $transaction->created_at->format('d F Y') }}</div>
                </div>
            </div>
        </div>

        <div class="billing-info">
            <div class="billing-from">
                <div class="section-title">Diterbitkan Atas Nama</div>
                <div class="company-name">E-Report System</div>
                <div class="info-text">Platform Manajemen Laporan Sekolah</div>
                <div class="info-text">support@e-report.com</div>
            </div>
            <div class="billing-to">
                <div class="section-title">Untuk</div>
                <div class="company-name">{{ $transaction->school->name }}</div>
                <div class="info-text">{{ $transaction->school->address ?? '-' }}</div>
                <div class="info-text">{{ $transaction->school->email }}</div>
                @if($transaction->school->phone)
                    <div class="info-text">{{ $transaction->school->phone }}</div>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-title">Detail Transaksi</div>
            <table>
                <thead>
                    <tr>
                        <th>Info Produk</th>
                        <th class="text-center" style="width: 80px;">Jumlah</th>
                        <th class="text-right" style="width: 120px;">Harga Satuan</th>
                        <th class="text-right" style="width: 120px;">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="product-name">{{ $transaction->package->name }}</div>
                            <div class="product-desc">Durasi: {{ $transaction->package->duration_months }} bulan</div>
                        </td>
                        <td class="text-center">1</td>
                        <td class="text-right">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</td>
                        <td class="text-right"><strong>Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="summary-box">
            <div class="summary-row">
                <div class="summary-label">Subtotal</div>
                <div class="summary-value">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</div>
            </div>
            <div class="summary-row total-row">
                <div class="summary-label">Total Tagihan</div>
                <div class="summary-value">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="payment-info">
            <div class="payment-col">
                <div class="section-title">Metode Pembayaran</div>
                <div style="font-weight: bold; margin-bottom: 5px;">
                    {{ $transaction->payment_method == 'gopay' ? 'QRIS Gopay' : ($transaction->payment_method ? ucwords(str_replace('_', ' ', $transaction->payment_method)) : 'Virtual Account') }}
                </div>
                @if($transaction->va_number)
                    <div class="info-text">Bank: {{ strtoupper($transaction->bank ?? 'BCA') }}</div>
                    <div class="info-text">VA: {{ $transaction->va_number }}</div>
                @endif
            </div>
            <div class="payment-col">
                <div class="section-title">Status Pembayaran</div>
                @php
                    $statusConfig = match($transaction->transaction_status) {
                        'success', 'settlement' => ['text' => 'Lunas', 'class' => 'status-paid'],
                        'pending' => ['text' => 'Menunggu Pembayaran', 'class' => 'status-pending'],
                        'expire', 'expired' => ['text' => 'Kadaluarsa', 'class' => 'status-expired'],
                        'cancel', 'deny' => ['text' => 'Dibatalkan', 'class' => 'status-expired'],
                        default => ['text' => ucfirst($transaction->transaction_status), 'class' => 'status-pending'],
                    };
                @endphp
                <div class="status-badge {{ $statusConfig['class'] }}">
                    {{ $statusConfig['text'] }}
                </div>
                @if($transaction->settlement_time)
                    <div class="info-text" style="margin-top: 8px;">
                        Dibayar: {{ $transaction->settlement_time->format('d M Y, H:i') }} WIB
                    </div>
                @endif
            </div>
        </div>

        <div class="footer">
            <div class="footer-note">
                Invoice ini sah dan diproses secara otomatis.<br>
                Silakan hubungi <strong>support@e-report.com</strong> apabila kamu membutuhkan bantuan.
            </div>
            @if(in_array($transaction->transaction_status, ['success', 'settlement']))
                <div class="footer-timestamp">
                    Terakhir diperbarui: {{ $transaction->updated_at->format('d F Y H:i') }} WIB
                </div>
            @endif
            <div class="footer-timestamp" style="margin-top: 10px;">
                &copy; PT. KREASI DIGITAL CREATIVE MINDS INDONESIA all rights reserved
            </div>
        </div>
    </div>
</body>
</html>
