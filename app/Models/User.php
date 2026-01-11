<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'school_id',
        'name',
        'email',
        'username',
        'password',
        'role',
        'nip_nisn',
        'phone',
        'email_preferences',
        'total_points',
        'current_streak',
        'last_activity_date',
        'avatar_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'locked_until' => 'datetime',
            'email_preferences' => 'array',
            'last_activity_date' => 'date',
            'total_points' => 'integer',
            'current_streak' => 'integer',
        ];
    }

    /**
     * Get default email preferences.
     */
    public static function defaultEmailPreferences(): array
    {
        return [
            'new_report' => true,
            'status_update' => true,
            'weekly_digest' => false,
            'comment_notification' => true,
        ];
    }

    /**
     * Get email preference value.
     */
    public function getEmailPreference(string $key): bool
    {
        $defaults = self::defaultEmailPreferences();
        $prefs = $this->email_preferences ?? [];
        return $prefs[$key] ?? ($defaults[$key] ?? false);
    }

    /**
     * Check if user wants to receive specific email type.
     */
    public function wantsEmail(string $type): bool
    {
        return $this->getEmailPreference($type);
    }

    /**
     * Get the school this user belongs to.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is a school admin.
     */
    public function isAdminSekolah(): bool
    {
        return $this->role === 'admin_sekolah';
    }

    /**
     * Check if user is kepala sekolah.
     */
    public function isManajemenSekolah(): bool
    {
        return $this->role === 'manajemen_sekolah';
    }



    /**
     * Check if user is student affairs staff.
     */
    public function isStafKesiswaan(): bool
    {
        return $this->role === 'staf_kesiswaan';
    }



    /**
     * Check if user is a teacher (guru or staf_kesiswaan).
     */
    public function isTeacher(): bool
    {
        return in_array($this->role, ['guru', 'staf_kesiswaan']);
    }

    /**
     * Check if user is a student.
     */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    /**
     * Check if account is currently locked.
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Increment failed login attempts.
     */
    public function incrementFailedAttempts(): void
    {
        $this->failed_login_attempts++;
        
        // Lock account after 5 failed attempts
        if ($this->failed_login_attempts >= 5) {
            $this->locked_until = now()->addMinutes(15);
        }
        
        $this->save();
    }

    /**
     * Reset failed login attempts on successful login.
     */
    public function resetFailedAttempts(): void
    {
        $this->failed_login_attempts = 0;
        $this->locked_until = null;
        $this->save();
    }

    /**
     * Get role display name in Indonesian.
     */
    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            'super_admin' => 'Super Admin',
            'admin_sekolah' => 'Admin Sekolah',
            'manajemen_sekolah' => 'Manajemen Sekolah',
            'staf_kesiswaan' => 'Staf Kesiswaan',
            'guru' => 'Guru',
            'siswa' => 'Siswa',
            default => 'Unknown',
        };
    }

    /**
     * Get reports where this user is an accused.
     */
    public function reportsAsAccused(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Report::class, 'report_accused', 'accused_user_id', 'report_id')
            ->withTimestamps();
    }

    /**
     * Get the total index (sum of all indexes).
     */
    public function getTotalIndexAttribute(): int
    {
        return $this->positive_index + $this->neutral_index + $this->negative_index;
    }

    /**
     * Get index summary as formatted string.
     */
    public function getIndexSummaryAttribute(): string
    {
        return sprintf('+%d / %d / -%d', 
            $this->positive_index, 
            $this->neutral_index, 
            $this->negative_index
        );
    }

    /**
     * Get number of times this user has been accused.
     */
    public function getAccusedCountAttribute(): int
    {
        return $this->reportsAsAccused()->count();
    }

    /**
     * Get badges earned by this user.
     */
    public function badges(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * Get points history for this user.
     */
    public function points(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserPoint::class);
    }

    /**
     * Get avatar URL or default UI Avatar.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path && Storage::disk('public')->exists($this->avatar_path)) {
            return Storage::url($this->avatar_path);
        }

        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
    }
}

