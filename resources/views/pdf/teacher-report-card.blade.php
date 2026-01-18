<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Raport Perilaku Guru/Staf</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .header h2 {
            font-size: 14px;
            font-weight: normal;
            color: #555;
        }
        .header p {
            font-size: 10px;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            background: #f0f0f0;
            padding: 8px 10px;
            margin-bottom: 10px;
            border-left: 4px solid #6366f1;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 120px;
            padding: 5px 0;
            font-weight: bold;
        }
        .info-value {
            display: table-cell;
            padding: 5px 0;
        }
        .stats-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .stats-grid td {
            width: 33%;
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
        }
        .stats-number {
            font-size: 24px;
            font-weight: bold;
            color: #6366f1;
        }
        .stats-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        table.report-table th {
            background: #6366f1;
            color: white;
            padding: 8px;
            text-align: left;
        }
        table.report-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        table.report-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-positif { background: #dcfce7; color: #166534; }
        .badge-negatif { background: #fef2f2; color: #991b1b; }
        .badge-netral { background: #f3f4f6; color: #374151; }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
            margin-top: 30px;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
            height: 50px;
        }
    </style>
</head>
<body>
    <div class="header">
        <p style="font-size: 10px; color: #6366f1; font-weight: bold; margin-bottom: 5px;">📄 e-Report - Sistem Pelaporan Digital</p>
        <h1>{{ $school->name }}</h1>
        <h2>Raport Perilaku Guru/Staf</h2>
        <p>{{ $school->address ?? 'Alamat Sekolah' }}</p>
    </div>

    <div class="section">
        <div class="section-title">Data Guru/Staf</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value">: {{ $user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">NIP</div>
                <div class="info-value">: {{ $user->nip_nisn ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Jabatan</div>
                <div class="info-value">: {{ $user->getRoleDisplayName() }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">: {{ $user->email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Cetak</div>
                <div class="info-value">: {{ now()->translatedFormat('d F Y') }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Ringkasan Laporan</div>
        <table class="stats-grid">
            <tr>
                <td>
                    <div class="stats-number">{{ $stats['reports_about'] }}</div>
                    <div class="stats-label">Total Laporan Terkait</div>
                </td>
                <td>
                    <div class="stats-number">{{ $stats['by_classification']['positif'] ?? 0 }}</div>
                    <div class="stats-label">Positif</div>
                </td>
                <td>
                    <div class="stats-number">{{ $stats['by_classification']['negatif'] ?? 0 }}</div>
                    <div class="stats-label">Negatif</div>
                </td>
            </tr>
        </table>
    </div>

    @if($reportsAbout->count() > 0)
    <div class="section">
        <div class="section-title">Riwayat Laporan Terkait Guru/Staf</div>
        <table class="report-table">
            <thead>
                <tr>
                    <th width="15%">Tanggal</th>
                    <th width="20%">Kategori</th>
                    <th width="40%">Judul</th>
                    <th width="15%">Klasifikasi</th>
                    <th width="10%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportsAbout->take(15) as $report)
                <tr>
                    <td>{{ $report->created_at->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($report->getCategory()) }}</td>
                    <td>{{ Str::limit($report->title, 40) }}</td>
                    <td>
                        <span class="badge badge-{{ $report->getClassification() ?? 'netral' }}">
                            {{ ucfirst($report->getClassification() ?? 'netral') }}
                        </span>
                    </td>
                    <td>{{ ucfirst($report->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($reportsAbout->count() > 15)
        <p style="margin-top: 10px; font-size: 10px; color: #666;">
            * Menampilkan 15 dari {{ $reportsAbout->count() }} laporan
        </p>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem E-Report pada {{ now()->translatedFormat('d F Y H:i') }}</p>
        
        <div class="signature-box">
            <p>{{ now()->translatedFormat('d F Y') }}</p>
            <p>Kepala Sekolah / Manajemen</p>
            <div class="signature-line"></div>
            <p>(_______________________)</p>
        </div>
    </div>
</body>
</html>
