<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard Analitik</title>
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
        .meeting-info {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            color: white;
            text-align: center;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 12px;
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
            border-left: 4px solid #3b82f6;
        }
        .stats-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .stats-row td {
            width: 20%;
            text-align: center;
            padding: 15px 10px;
            border: 1px solid #ddd;
            background: #fff;
        }
        .stats-number {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
        }
        .stats-number.green { color: #16a34a; }
        .stats-number.yellow { color: #ca8a04; }
        .stats-number.purple { color: #9333ea; }
        .stats-label {
            font-size: 9px;
            color: #666;
            margin-top: 5px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        table.data-table th {
            background: #374151;
            color: white;
            padding: 8px;
            text-align: left;
        }
        table.data-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        table.data-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .two-column {
            width: 100%;
        }
        .two-column > tbody > tr > td {
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }
        .trend-table td {
            text-align: center;
            padding: 10px 5px;
            border: 1px solid #ddd;
        }
        .trend-month {
            font-size: 9px;
            color: #666;
        }
        .trend-count {
            font-size: 16px;
            font-weight: bold;
            color: #3b82f6;
        }
        .classification-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .classification-grid td {
            width: 33.33%;
            text-align: center;
            padding: 15px;
        }
        .class-positif { background: #dcfce7; }
        .class-netral { background: #f3f4f6; }
        .class-negatif { background: #fef2f2; }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
        .signature-row {
            width: 100%;
            margin-top: 40px;
        }
        .signature-row td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 10px;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            margin: 40px 20px 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $school->name }}</h1>
        <h2>Dashboard Analitik E-Report</h2>
    </div>

    <div class="meeting-info">
        📊 Laporan untuk Rapat Koordinasi<br>
        <span style="font-size: 10px;">Dicetak pada: {{ now()->translatedFormat('l, d F Y') }}</span>
    </div>

    <div class="section">
        <div class="section-title">Statistik Utama</div>
        <table class="stats-row">
            <tr>
                <td>
                    <div class="stats-number">{{ $stats['totalReports'] }}</div>
                    <div class="stats-label">Total Laporan</div>
                </td>
                <td>
                    <div class="stats-number purple">{{ $stats['thisMonthReports'] }}</div>
                    <div class="stats-label">Bulan Ini</div>
                </td>
                <td>
                    <div class="stats-number green">{{ $stats['completedReports'] }}</div>
                    <div class="stats-label">Selesai</div>
                </td>
                <td>
                    <div class="stats-number yellow">{{ $stats['pendingReports'] }}</div>
                    <div class="stats-label">Pending</div>
                </td>
                <td>
                    <div class="stats-number">{{ $stats['totalUsers'] }}</div>
                    <div class="stats-label">Total Pengguna</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Tren 6 Bulan Terakhir</div>
        <table class="data-table trend-table">
            <tr>
                @foreach($monthlyTrend as $trend)
                <td>
                    <div class="trend-count">{{ $trend['count'] }}</div>
                    <div class="trend-month">{{ $trend['month'] }}</div>
                </td>
                @endforeach
            </tr>
        </table>
    </div>

    <table class="two-column">
        <tr>
            <td>
                <div class="section">
                    <div class="section-title">Laporan per Kategori</div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th width="80">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportsByCategory as $category => $count)
                            <tr>
                                <td>{{ ucfirst($category) }}</td>
                                <td>{{ $count }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" style="text-align: center;">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </td>
            <td>
                <div class="section">
                    <div class="section-title">Laporan per Status</div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th width="80">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportsByStatus as $status => $count)
                            <tr>
                                <td>{{ ucfirst($status) }}</td>
                                <td>{{ $count }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" style="text-align: center;">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <div class="section-title">Klasifikasi Laporan</div>
        <table class="classification-grid">
            <tr>
                <td class="class-positif">
                    <div class="stats-number green">{{ $classificationStats['positif'] ?? 0 }}</div>
                    <div class="stats-label">Positif</div>
                </td>
                <td class="class-netral">
                    <div class="stats-number">{{ $classificationStats['netral'] ?? 0 }}</div>
                    <div class="stats-label">Netral</div>
                </td>
                <td class="class-negatif">
                    <div class="stats-number" style="color: #dc2626;">{{ $classificationStats['negatif'] ?? 0 }}</div>
                    <div class="stats-label">Negatif</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Top 5 Pelapor</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="10%">No</th>
                    <th>Nama</th>
                    <th width="25%">Peran</th>
                    <th width="15%">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topReporters as $index => $reporter)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $reporter->name }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $reporter->role)) }}</td>
                    <td>{{ $reporter->total }} laporan</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem E-Report untuk keperluan rapat.</p>
        
        <table class="signature-row">
            <tr>
                <td>
                    <p>Mengetahui,</p>
                    <p>Kepala Sekolah</p>
                    <div class="signature-line"></div>
                    <p>(_______________________)</p>
                </td>
                <td>
                    <p>Koordinator BK</p>
                    <p>&nbsp;</p>
                    <div class="signature-line"></div>
                    <p>(_______________________)</p>
                </td>
                <td>
                    <p>Admin Sekolah</p>
                    <p>&nbsp;</p>
                    <div class="signature-line"></div>
                    <p>(_______________________)</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
