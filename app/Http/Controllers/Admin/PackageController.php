<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPackage;
use App\Models\Promotion;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    /**
     * Display package and promotion management dashboard.
     */
    public function index()
    {
        $packages = SubscriptionPackage::orderBy('sort_order')->orderBy('price')->get();
        $promotions = Promotion::orderBy('created_at', 'desc')->get();
        
        $stats = [
            'total_packages' => $packages->count(),
            'active_packages' => $packages->where('is_active', true)->count(),
            'total_promotions' => $promotions->count(),
            'active_promotions' => $promotions->filter(fn($p) => $p->isValid())->count(),
        ];

        return view('admin.packages.index', compact('packages', 'promotions', 'stats'));
    }

    // ========== PACKAGE METHODS ==========

    /**
     * Show create package form.
     */
    public function createPackage()
    {
        return view('admin.packages.create-package');
    }

    /**
     * Store a new package.
     */
    public function storePackage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_users' => 'required|integer|min:1',
            'max_reports_per_month' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:20',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        DB::beginTransaction();
        try {
            $package = SubscriptionPackage::create($validated);
            
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'create_package',
                'model_type' => SubscriptionPackage::class,
                'model_id' => $package->id,
                'description' => "Membuat paket baru: {$package->name}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            return redirect()->route('admin.packages')->with('success', 'Paket berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat paket: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show edit package form.
     */
    public function editPackage(SubscriptionPackage $package)
    {
        return view('admin.packages.edit-package', compact('package'));
    }

    /**
     * Update a package.
     */
    public function updatePackage(Request $request, SubscriptionPackage $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_users' => 'required|integer|min:1',
            'max_reports_per_month' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:20',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);

        DB::beginTransaction();
        try {
            $package->update($validated);
            
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'update_package',
                'model_type' => SubscriptionPackage::class,
                'model_id' => $package->id,
                'description' => "Mengupdate paket: {$package->name}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            return redirect()->route('admin.packages')->with('success', 'Paket berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate paket: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Toggle package status.
     */
    public function togglePackage(SubscriptionPackage $package)
    {
        $package->update(['is_active' => !$package->is_active]);
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $package->is_active ? 'activate_package' : 'deactivate_package',
            'model_type' => SubscriptionPackage::class,
            'model_id' => $package->id,
            'description' => ($package->is_active ? 'Mengaktifkan' : 'Menonaktifkan') . " paket: {$package->name}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $package->is_active,
            'message' => 'Status paket berhasil diubah!',
        ]);
    }

    /**
     * Delete a package (requires password confirmation).
     */
    public function deletePackage(Request $request, SubscriptionPackage $package)
    {
        // Validate password
        $request->validate([
            'password' => 'required|string',
        ]);

        // Verify password
        if (!\Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah. Silakan coba lagi.',
            ], 403);
        }

        // Check if package has active subscriptions
        if ($package->subscriptions()->where('status', 'active')->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus paket yang memiliki langganan aktif!',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $packageName = $package->name;
            $packageId = $package->id;
            
            $package->delete();
            
            // Clear cache
            cache()->forget('subscription_packages');
            
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'delete_package',
                'model_type' => SubscriptionPackage::class,
                'model_id' => $packageId,
                'description' => "Menghapus paket: {$packageName} (dengan verifikasi password)",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Paket berhasil dihapus!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus paket: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Reorder packages.
     */
    public function reorderPackages(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:subscription_packages,id',
            'orders.*.order' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['orders'] as $item) {
                SubscriptionPackage::where('id', $item['id'])->update(['sort_order' => $item['order']]);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Urutan paket berhasil diubah!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal mengubah urutan!'], 500);
        }
    }

    // ========== PROMOTION METHODS ==========

    /**
     * Show create promotion form.
     */
    public function createPromotion()
    {
        $packages = SubscriptionPackage::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.packages.create-promotion', compact('packages'));
    }

    /**
     * Store a new promotion.
     */
    public function storePromotion(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'integer|min:1',
            'applicable_packages' => 'nullable|array',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['usage_per_user'] = $validated['usage_per_user'] ?? 1;
        $validated['min_purchase'] = $validated['min_purchase'] ?? 0;

        DB::beginTransaction();
        try {
            $promotion = Promotion::create($validated);
            
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'create_promotion',
                'model_type' => Promotion::class,
                'model_id' => $promotion->id,
                'description' => "Membuat promosi baru: {$promotion->name} ({$promotion->code})",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            return redirect()->route('admin.packages')->with('success', 'Promosi berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat promosi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show edit promotion form.
     */
    public function editPromotion(Promotion $promotion)
    {
        $packages = SubscriptionPackage::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.packages.edit-promotion', compact('promotion', 'packages'));
    }

    /**
     * Update a promotion.
     */
    public function updatePromotion(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code,' . $promotion->id,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'integer|min:1',
            'applicable_packages' => 'nullable|array',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active', true);

        DB::beginTransaction();
        try {
            $promotion->update($validated);
            
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'update_promotion',
                'model_type' => Promotion::class,
                'model_id' => $promotion->id,
                'description' => "Mengupdate promosi: {$promotion->name}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            return redirect()->route('admin.packages')->with('success', 'Promosi berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate promosi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Toggle promotion status.
     */
    public function togglePromotion(Promotion $promotion)
    {
        $promotion->update(['is_active' => !$promotion->is_active]);
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $promotion->is_active ? 'activate_promotion' : 'deactivate_promotion',
            'model_type' => Promotion::class,
            'model_id' => $promotion->id,
            'description' => ($promotion->is_active ? 'Mengaktifkan' : 'Menonaktifkan') . " promosi: {$promotion->name}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $promotion->is_active,
            'message' => 'Status promosi berhasil diubah!',
        ]);
    }

    /**
     * Delete a promotion.
     */
    public function deletePromotion(Promotion $promotion)
    {
        DB::beginTransaction();
        try {
            $promotionName = $promotion->name;
            $promotionId = $promotion->id;
            
            $promotion->delete();
            
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'delete_promotion',
                'model_type' => Promotion::class,
                'model_id' => $promotionId,
                'description' => "Menghapus promosi: {$promotionName}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Promosi berhasil dihapus!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus promosi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Validate a promo code (API endpoint for checkout).
     */
    public function validatePromoCode(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'package_id' => 'required|exists:subscription_packages,id',
            'school_id' => 'required|exists:schools,id',
        ]);

        $promotion = Promotion::where('code', strtoupper($validated['code']))->first();

        if (!$promotion) {
            return response()->json(['valid' => false, 'message' => 'Kode promo tidak ditemukan.']);
        }

        if (!$promotion->isValid()) {
            return response()->json(['valid' => false, 'message' => 'Kode promo sudah tidak berlaku.']);
        }

        if (!$promotion->canBeUsedBy($validated['school_id'])) {
            return response()->json(['valid' => false, 'message' => 'Anda sudah menggunakan kode promo ini.']);
        }

        if (!$promotion->appliesToPackage($validated['package_id'])) {
            return response()->json(['valid' => false, 'message' => 'Kode promo tidak berlaku untuk paket ini.']);
        }

        $package = SubscriptionPackage::find($validated['package_id']);
        $discount = $promotion->calculateDiscount($package->price);

        return response()->json([
            'valid' => true,
            'promotion' => [
                'id' => $promotion->id,
                'code' => $promotion->code,
                'name' => $promotion->name,
                'type' => $promotion->type,
                'value' => $promotion->value,
                'formatted_value' => $promotion->formatted_value,
            ],
            'discount' => $discount,
            'formatted_discount' => 'Rp ' . number_format($discount, 0, ',', '.'),
            'final_price' => $package->price - $discount,
            'formatted_final_price' => 'Rp ' . number_format($package->price - $discount, 0, ',', '.'),
        ]);
    }

    /**
     * Sync packages to landing page (update cache/config).
     */
    public function syncPackages()
    {
        try {
            // Clear any cached package data
            cache()->forget('subscription_packages');
            cache()->forget('active_promotions');
            
            // Recache active packages
            $packages = SubscriptionPackage::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
            cache()->put('subscription_packages', $packages, now()->addHours(24));
            
            // Recache active promotions
            $promotions = Promotion::valid()->get();
            cache()->put('active_promotions', $promotions, now()->addHours(1));
            
            return response()->json([
                'success' => true,
                'message' => 'Paket dan promosi berhasil disinkronkan!',
                'synced' => [
                    'packages' => $packages->count(),
                    'promotions' => $promotions->count(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyinkronkan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
