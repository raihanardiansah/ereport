<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'code',
        'location_name',
        'description',
        'default_category',
        'created_by',
        'scan_count',
        'last_scanned_at',
        'is_active',
    ];

    protected $casts = [
        'last_scanned_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the school.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate a new QR code for a school.
     */
    public static function generate(
        int $schoolId,
        string $locationName,
        int $createdBy,
        ?string $description = null,
        ?string $defaultCategory = null
    ): self {
        return self::create([
            'school_id' => $schoolId,
            'code' => self::generateUniqueCode(),
            'location_name' => $locationName,
            'description' => $description,
            'default_category' => $defaultCategory,
            'created_by' => $createdBy,
            'is_active' => true,
        ]);
    }

    /**
     * Generate a unique QR code string.
     */
    protected static function generateUniqueCode(): string
    {
        do {
            $code = 'QR-' . strtoupper(Str::random(24));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Record a scan.
     */
    public function recordScan(): void
    {
        $this->increment('scan_count');
        $this->update(['last_scanned_at' => now()]);
    }

    /**
     * Get the URL for this QR code.
     */
    public function getUrlAttribute(): string
    {
        return route('qr.scan', $this->code);
    }

    /**
     * Find QR code by code string.
     */
    public static function findByCode(string $code): ?self
    {
        return self::where('code', $code)
            ->where('is_active', true)
            ->first();
    }
}
