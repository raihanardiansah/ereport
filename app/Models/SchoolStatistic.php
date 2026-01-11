<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'period',
        'total_reports',
        'resolved_reports',
        'escalated_reports',
        'avg_resolution_hours',
        'positive_count',
        'negative_count',
        'neutral_count',
        'anonymous_reports',
    ];

    protected $casts = [
        'total_reports' => 'integer',
        'resolved_reports' => 'integer',
        'escalated_reports' => 'integer',
        'avg_resolution_hours' => 'float',
        'positive_count' => 'integer',
        'negative_count' => 'integer',
        'neutral_count' => 'integer',
        'anonymous_reports' => 'integer',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get resolution rate as percentage.
     */
    public function getResolutionRateAttribute(): float
    {
        if ($this->total_reports === 0) {
            return 0;
        }
        return round(($this->resolved_reports / $this->total_reports) * 100, 1);
    }

    /**
     * Get primary sentiment.
     */
    public function getPrimarySentimentAttribute(): string
    {
        $max = max($this->positive_count, $this->negative_count, $this->neutral_count);
        
        if ($max === $this->positive_count) return 'positif';
        if ($max === $this->negative_count) return 'negatif';
        return 'netral';
    }
}
