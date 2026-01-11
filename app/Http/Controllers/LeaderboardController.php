<?php

namespace App\Http\Controllers;

use App\Services\GamificationService;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    protected GamificationService $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    public function index()
    {
        $user = auth()->user();
        
        // Get top 10 users in the school
        $leaderboard = $this->gamificationService->getSchoolLeaderboard($user->school_id);
        
        // Get current user's rank
        $myRank = $this->gamificationService->getLeaderboardPosition($user);
        
        // Get my badges
        $myBadges = $user->badges;

        return view('leaderboard.index', compact('leaderboard', 'myRank', 'myBadges', 'user'));
    }
}
