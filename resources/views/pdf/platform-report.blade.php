<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Platform E-Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #22c55e;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #22c55e;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .stats-row {
            display: table-row;
        }
        .stat-box {
            display: table-cell;
            width: 16.66%;
            padding: 10px;
            text-align: center;
            border: 1px solid #eee;
            background: #f9f9f9;
        }
        .stat-box .value {
            font-size: 24px;
            font-weight: bold;
            color: #22c55e;
        }
        .stat-box .label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background: #f5f5f5;
            font-weight: bold;
            font-size: 11px;
        }
        table td {
            font-size: 11px;
        }
        .category-bar {
            height: 20px;
            background: #e5e5e5;
            margin: 5px 0;
            position: relative;
        }
        .category-bar-fill {
            height: 100%;
            background: #22c55e;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Laporan Platform E-Report</h1>
        <p>Tanggal: {{ date('d F Y') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Statistik Platform</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stat-box">
                    <div class="value">{{ $stats['total_schools'] }}</div>
                    <div class="label">Total Sekolah</div>
                </div>
                <div class="stat-box">
                    <div class="value">{{ $stats['active_schools'] }}</div>
                    <div class="label">Sekolah Aktif</div>
                </div>
                <div class="stat-box">
                    <div class="value">{{ $stats['trial_schools'] }}</div>
                    <div class="label">Trial</div>
                </div>
                <div class="stat-box">
                    <div class="value">{{ number_format($stats['total_users']) }}</div>
                    <div class="label">Total User</div>
                </div>
                <div class="stat-box">
                    <div class="value">{{ number_format($stats['total_reports']) }}</div>
                    <div class="label">Total Laporan</div>
                </div>
                <div class="stat-box">
                    <div class="value">{{ $stats['total_cases'] }}</div>
                    <div class="label">Kasus Siswa</div>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Laporan per Kategori</div>
        @php $totalCat = array_sum($reportsByCategory) ?: 1; @endphp
        @foreach($reportsByCategory as $category => $count)
        <div style="margin-bottom: 8px;">
            <div style="display: flex; justify-content: space-between;">
                <span style="text-transform: capitalize;">{{ $category }}</span>
                <span>{{ $count }} ({{ round(($count / $totalCat) * 100) }}%)</span>
            </div>
            <div class="category-bar">
                <div class="category-bar-fill" style="width: {{ ($count / $totalCat) * 100 }}%"></div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="section">
        <div class="section-title">Top 10 Sekolah Berdasarkan Aktivitas</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Sekolah</th>
                    <th>NPSN</th>
                    <th>Jumlah User</th>
                    <th>Jumlah Laporan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topSchools as $index => $school)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $school->name }}</td>
                    <td>{{ $school->npsn ?? '-' }}</td>
                    <td>{{ $school->users_count }}</td>
                    <td>{{ $school->reports_count }}</td>
                    <td>{{ ucfirst($school->subscription_status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem E-Report</p>
        <p>{{ date('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
