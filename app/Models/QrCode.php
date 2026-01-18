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
        'name',
        'location',
        'type',
        'metadata',
        'scan_count',
        'last_scanned_at',
        'is_active',
    ];

    protected $casts = [
        'metadata' => 'array',
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

    /**
     * Generate a new QR code for a school.
     */
    public static function generate(
        int $schoolId,
        string $name,
        string $type = 'general',
        ?string $location = null,
        ?array $metadata = null
    ): self {
        return self::create([
            'school_id' => $schoolId,
            'code' => self::generateUniqueCode(),
            'name' => $name,
            'type' => $type,
            'location' => $location,
            'metadata' => $metadata,
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
        return route('qr.report', $this->code);
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
