<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ringkasan {{ ucfirst($type) }}</title>
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
        .period-box {
            background: #3b82f6;
            color: white;
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: bold;
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
        .stats-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .stats-grid td {
            width: 33.33%;
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
        }
        .stats-number {
            font-size: 28px;
            font-weight: bold;
            color: #3b82f6;
        }
        .stats-number.green { color: #16a34a; }
        .stats-number.yellow { color: #ca8a04; }
        .stats-number.red { color: #dc2626; }
        .stats-label {
            font-size: 10px;
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
        .two-column td {
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }
        .bar {
            height: 20px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
        }
        .bar-fill {
            height: 100%;
            background: #3b82f6;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $school->name }}</h1>
        <h2>Ringkasan Laporan {{ $type === 'semester' ? 'Semester' : 'Bulanan' }}</h2>
    </div>

    <div class="period-box">
        Periode: {{ $periodLabel }}<br>
        <span style="font-size: 10px; font-weight: normal;">
            {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
        </span>
    </div>

    <div class="section">
        <div class="section-title">Statistik Utama</div>
        <table class="stats-grid">
            <tr>
                <td>
                    <div class="stats-number">{{ $stats['total'] }}</div>
                    <div class="stats-label">Total Laporan</div>
                </td>
                <td>
                    <div class="stats-number green">{{ $stats['completed'] }}</div>
                    <div class="stats-label">Selesai</div>
                </td>
                <td>
                    <div class="stats-number yellow">{{ $stats['pending'] }}</div>
                    <div class="stats-label">Pending</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="two-column">
        <tr>
            <td>
                <div class="section">
                    <div class="section-title">Berdasarkan Kategori</div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th width="60">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['by_category'] as $category => $count)
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
                    <div class="section-title">Berdasarkan Status</div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th width="60">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['by_status'] as $status => $count)
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
        <table class="stats-grid">
            <tr>
                <td style="background: #dcfce7;">
                    <div class="stats-number green">{{ $stats['by_classification']['positif'] ?? 0 }}</div>
                    <div class="stats-label">Positif</div>
                </td>
                <td>
                    <div class="stats-number">{{ $stats['by_classification']['netral'] ?? 0 }}</div>
                    <div class="stats-label">Netral</div>
                </td>
                <td style="background: #fef2f2;">
                    <div class="stats-number red">{{ $stats['by_classification']['negatif'] ?? 0 }}</div>
                    <div class="stats-label">Negatif</div>
                </td>
            </tr>
        </table>
    </div>

    @if($reports->count() > 0)
    <div class="section">
        <div class="section-title">Daftar Laporan Terbaru</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="12%">Tanggal</th>
                    <th width="20%">Pengirim</th>
                    <th width="15%">Kategori</th>
                    <th width="38%">Judul</th>
                    <th width="15%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports->take(20) as $report)
                <tr>
                    <td>{{ $report->created_at->format('d/m/Y') }}</td>
                    <td>{{ Str::limit($report->user->name ?? '-', 20) }}</td>
                    <td>{{ ucfirst($report->category) }}</td>
                    <td>{{ Str::limit($report->title, 35) }}</td>
                    <td>{{ ucfirst($report->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($reports->count() > 20)
        <p style="margin-top: 10px; font-size: 10px; color: #666;">
            * Menampilkan 20 dari {{ $reports->count() }} laporan
        </p>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem E-Report</p>
        <p>{{ now()->translatedFormat('d F Y H:i') }}</p>
    </div>
</body>
</html>
