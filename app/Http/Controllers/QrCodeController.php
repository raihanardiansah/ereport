<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCodeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['admin_sekolah', 'manajemen_sekolah'])) {
            abort(403);
        }

        $qrCodes = QrCode::where('school_id', $user->school_id)
            ->with('creator')
            ->latest()
            ->paginate(10);

        return view('settings.qr-codes.index', compact('qrCodes'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['admin_sekolah', 'manajemen_sekolah'])) {
            abort(403);
        }

        $validated = $request->validate([
            'location_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'default_category' => 'nullable|string|in:perilaku,akademik,kehadiran,bullying,konseling,kesehatan,fasilitas,prestasi,keamanan,ekstrakurikuler,sosial,keuangan,kebersihan,kantin,transportasi,teknologi,guru,kurikulum,perpustakaan,laboratorium,olahraga,keagamaan,saran,lainnya',
        ]);

        QrCode::generate(
            $user->school_id,
            $validated['location_name'],
            $user->id,
            $validated['description'],
            $validated['default_category']
        );

        return redirect()->back()->with('success', 'QR Code berhasil dibuat!');
    }

    public function show(QrCode $qrCode)
    {
        $user = auth()->user();
        if ($user->school_id !== $qrCode->school_id || !$user->hasAnyRole(['admin_sekolah', 'manajemen_sekolah'])) {
            abort(403);
        }

        return view('settings.qr-codes.print', compact('qrCode'));
    }

    public function destroy(QrCode $qrCode)
    {
        $user = auth()->user();
        if ($user->school_id !== $qrCode->school_id || !$user->hasAnyRole(['admin_sekolah', 'manajemen_sekolah'])) {
            abort(403);
        }

        $qrCode->delete();
        return redirect()->back()->with('success', 'QR Code berhasil dihapus!');
    }

    public function handle($code)
    {
        $qrCode = QrCode::where('code', $code)->firstOrFail();
        
        if (!$qrCode->is_active) {
            abort(404, 'QR Code tidak aktif.');
        }

        $qrCode->recordScan();

        return redirect()->route('reports.create', [
            'location' => $qrCode->location_name,
            'category' => $qrCode->default_category,
            'source' => 'qr_code'
        ]);
    }
}
