<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class DashboardController extends Controller
{
    protected $database;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(env('FIREBASE_CREDENTIALS'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $this->database = $firebase->createDatabase();
    }

    public function index()
    {
        // Fetch all plans
        $allPlans = $this->database->getReference('plans')->getValue() ?? [];

        $planCounts = [
            'medical' => 0,
            'critical' => 0,
            'life' => 0,
        ];

        foreach ($allPlans as $plan) {
            $cat = strtolower($plan['category'] ?? '');
            if (isset($planCounts[$cat])) {
                $planCounts[$cat]++;
            }
        }

        // Fetch pending quote requests
        $allRequests = $this->database->getReference('quote_requests')->getValue() ?? [];
        $pendingCount = collect($allRequests)
            ->filter(fn($r) => ($r['status'] ?? 'pending') === 'pending')
            ->count();

        // Fetch registered users
        $allUsers = $this->database->getReference('users')->getValue() ?? [];
        $userCount = count($allUsers);

        return view('user.dashboard', compact('planCounts', 'pendingCount', 'userCount'));
    }
}
