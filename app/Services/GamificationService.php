<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class GamificationService
{
    /**
     * Points configuration.
     */
    protected array $pointsConfig = [
        'report_submitted' => 10,
        'report_resolved' => 5,
        'streak_bonus' => 2,
        'first_report' => 20,
    ];

    /**
     * Award points to a user.
     */
    public function awardPoints(User $user, string $action, ?Model $source = null, ?string $description = null): int
    {
        $points = $this->pointsConfig[$action] ?? 0;
        
        if ($points === 0) {
            return 0;
        }

        // Check for streak bonus
        if ($action === 'report_submitted') {
            $this->updateStreak($user);
            if ($user->current_streak > 1) {
                $points += $this->pointsConfig['streak_bonus'] * min($user->current_streak, 7);
            }
        }

        // Record the points
        UserPoint::create([
            'user_id' => $user->id,
            'points' => $points,
            'action' => $action,
            'description' => $description ?? $this->getDefaultDescription($action),
            'pointable_type' => $source ? get_class($source) : null,
            'pointable_id' => $source?->id,
        ]);

        // Update user's total points
        $user->increment('total_points', $points);

        // Check for badge eligibility
        $this->checkBadgeEligibility($user);

        Log::info('Points awarded', [
            'user_id' => $user->id,
            'action' => $action,
            'points' => $points,
            'total' => $user->fresh()->total_points,
        ]);

        return $points;
    }

    /**
     * Update user streak.
     */
    protected function updateStreak(User $user): void
    {
        $today = now()->toDateString();
        $lastActivity = $user->last_activity_date;

        if ($lastActivity === null) {
            // First activity ever
            $user->update([
                'current_streak' => 1,
                'last_activity_date' => $today,
            ]);
        } elseif ($lastActivity->toDateString() === now()->subDay()->toDateString()) {
            // Consecutive day - increase streak
            $user->update([
                'current_streak' => $user->current_streak + 1,
                'last_activity_date' => $today,
            ]);
        } elseif ($lastActivity->toDateString() !== $today) {
            // Streak broken - reset to 1
            $user->update([
                'current_streak' => 1,
                'last_activity_date' => $today,
            ]);
        }
        // Same day - do nothing
    }

    /**
     * Check and award badges based on criteria.
     */
    public function checkBadgeEligibility(User $user): array
    {
        $earnedBadges = [];
        $activeBadges = Badge::active()->get();

        foreach ($activeBadges as $badge) {
            // Skip if user already has this badge
            if ($user->badges()->where('badge_id', $badge->id)->exists()) {
                continue;
            }

            $eligible = match ($badge->criteria_type) {
                'report_count' => $user->reports()->count() >= $badge->criteria_value,
                'consecutive_days' => $user->current_streak >= $badge->criteria_value,
                'first_action' => $this->checkFirstAction($user, $badge),
                'points_threshold' => $user->total_points >= $badge->criteria_value,
                default => false,
            };

            if ($eligible) {
                $this->awardBadge($user, $badge);
                $earnedBadges[] = $badge;
            }
        }

        return $earnedBadges;
    }

    /**
     * Check first action criteria.
     */
    protected function checkFirstAction(User $user, Badge $badge): bool
    {
        return match ($badge->slug) {
            'first-report' => $user->reports()->count() === 1,
            default => false,
        };
    }

    /**
     * Award a badge to user.
     */
    public function awardBadge(User $user, Badge $badge): void
    {
        // Attach badge
        $user->badges()->attach($badge->id, [
            'earned_at' => now(),
        ]);

        // Create notification
        \App\Services\Notification::create([
            'user_id' => $user->id,
            'title' => '🏆 Badge Baru Diperoleh!',
            'message' => "Selamat! Anda mendapatkan badge \"{$badge->name}\"",
            'type' => 'badge',
            'data' => [
                'badge_id' => $badge->id,
                'badge_name' => $badge->name,
                'badge_icon' => $badge->icon,
            ],
        ]);

        Log::info('Badge awarded', [
            'user_id' => $user->id,
            'badge_id' => $badge->id,
            'badge_name' => $badge->name,
        ]);
    }

    /**
     * Get default description for action.
     */
    protected function getDefaultDescription(string $action): string
    {
        return match ($action) {
            'report_submitted' => 'Poin untuk mengirim laporan',
            'report_resolved' => 'Poin karena laporan selesai ditangani',
            'streak_bonus' => 'Bonus aktivitas berturut-turut',
            'first_report' => 'Bonus laporan pertama',
            default => 'Poin aktivitas',
        };
    }

    /**
     * Get user's leaderboard position in their school.
     */
    public function getLeaderboardPosition(User $user): int
    {
        return User::where('school_id', $user->school_id)
            ->where('total_points', '>', $user->total_points)
            ->count() + 1;
    }

    /**
     * Get school leaderboard.
     */
    public function getSchoolLeaderboard(int $schoolId, int $limit = 10): \Illuminate\Support\Collection
    {
        return User::where('school_id', $schoolId)
            ->where('total_points', '>', 0)
            ->orderByDesc('total_points')
            ->limit($limit)
            ->get(['id', 'name', 'role', 'total_points', 'current_streak']);
    }
}
