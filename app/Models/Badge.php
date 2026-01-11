<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'criteria_type',
        'criteria_value',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'criteria_value' => 'integer',
    ];

    /**
     * Users who have earned this badge.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * Get badge color class for Tailwind.
     */
    public function getColorClassAttribute(): string
    {
        return match ($this->color) {
            'gold' => 'bg-amber-100 text-amber-800 border-amber-300',
            'silver' => 'bg-gray-100 text-gray-700 border-gray-300',
            'bronze' => 'bg-orange-100 text-orange-800 border-orange-300',
            'blue' => 'bg-blue-100 text-blue-800 border-blue-300',
            'green' => 'bg-green-100 text-green-800 border-green-300',
            'purple' => 'bg-purple-100 text-purple-800 border-purple-300',
            'red' => 'bg-red-100 text-red-800 border-red-300',
            default => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }

    /**
     * Scope to only active badges.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
