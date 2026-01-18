<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'category',
        'assigned_user_id',
        'is_active',
    ];

    protected $casts = [
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
     * Get the assigned user.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * Get auto-assignment for a category in a school.
     */
    public static function getAssignedUser(int $schoolId, string $category): ?User
    {
        $assignment = self::where('school_id', $schoolId)
            ->where('category', $category)
            ->where('is_active', true)
            ->first();

        return $assignment?->assignedUser;
    }

    /**
     * Set or update auto-assignment for a category.
     */
    public static function setAssignment(int $schoolId, string $category, ?int $userId): self
    {
        return self::updateOrCreate(
            [
                'school_id' => $schoolId,
                'category' => $category,
            ],
            [
                'assigned_user_id' => $userId,
                'is_active' => $userId !== null,
            ]
        );
    }
}
