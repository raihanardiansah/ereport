<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print QR Code - {{ $qrCode->location_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
                visibility: hidden !important;
            }

            body {
                margin: 0;
                padding: 0;
                background: white;
            }

            .print-area {
                border: none !important;
                box-shadow: none !important;
                margin: 0 auto;
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-6">

    <div class="no-print mb-8 flex gap-4">
        <button onclick="window.print()"
            class="bg-primary-600 hover:bg-primary-700 text-gray-800 font-bold py-2 px-6 rounded-lg shadow transition-colors">
            Print QR Code
        </button>
        <button onclick="window.close()"
            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition-colors">
            Tutup
        </button>
    </div>

    <!-- Print Card -->
    <div
        class="print-area bg-white p-12 rounded-3xl shadow-xl border-4 border-gray-900 max-w-sm w-full text-center relative overflow-hidden">

        <!-- Decoration -->
        <div class="absolute top-0 left-0 w-full h-4 bg-gray-900"></div>
        <div class="absolute bottom-0 left-0 w-full h-4 bg-gray-900"></div>

        <!-- e-Report Branding -->
        <div class="mb-6 pb-4 border-b-2 border-gray-200">
            <div class="flex items-center justify-center gap-2 mb-2">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-2xl font-black text-primary-600">e-Report</h3>
            </div>
            <p class="text-xs text-gray-500 uppercase tracking-wider">Sistem Pelaporan Digital</p>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-400 uppercase tracking-widest">{{ $qrCode->school->name }}</h2>
            <h1 class="text-3xl font-black text-gray-900 mt-2">{{ $qrCode->location_name }}</h1>
            @if($qrCode->default_category)
                <span class="inline-block mt-2 px-3 py-1 bg-gray-100 rounded-full text-sm font-semibold text-gray-600">
                    Kategori: {{ ucfirst($qrCode->default_category) }}
                </span>
            @endif
        </div>

        <div class="flex justify-center mb-6">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ $qrCode->url }}" alt="QR Code"
                class="w-64 h-64 border-4 border-white shadow-lg">
        </div>

        <div class="text-gray-600">
            <p class="font-medium mb-1">SCAN UNTUK MELAPOR</p>
            <p class="text-xs text-gray-400">Gunakan kamera HP atau aplikasi scanner</p>
        </div>

        <div class="mt-8 pt-4 border-t border-gray-100 text-xs text-gray-400 font-mono">
            {{ $qrCode->code }}
        </div>
    </div>

</body>

</html>