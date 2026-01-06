<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportTemplate extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'category',
        'title_template',
        'content_template',
        'description',
        'icon',
        'is_active',
        'is_global',
        'usage_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_global' => 'boolean',
    ];

    /**
     * Get the school that owns this template.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Scope for active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for global templates.
     */
    public function scopeGlobal($query)
    {
        return $query->where('is_global', true);
    }

    /**
     * Get templates available for a specific school.
     */
    public static function forSchool($schoolId)
    {
        return static::active()
            ->where(function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId)
                  ->orWhere('is_global', true);
            })
            ->orderBy('category')
            ->orderBy('name')
            ->get();
    }

    /**
     * Increment usage counter.
     */
    public function recordUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Get category icon.
     */
    public function getCategoryIconAttribute(): string
    {
        $icons = [
            'perilaku' => '⚠️',
            'akademik' => '📚',
            'sosial' => '👥',
            'fasilitas' => '🏫',
            'keamanan' => '🔒',
            'lainnya' => '📝',
        ];

        return $this->icon ?? ($icons[$this->category] ?? '📋');
    }
}
