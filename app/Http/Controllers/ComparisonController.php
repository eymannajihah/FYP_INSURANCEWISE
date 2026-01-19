<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class ComparisonController extends Controller
{
    protected $database;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(env('FIREBASE_CREDENTIALS'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $this->database = $firebase->createDatabase();
    }

    // SHOW PLAN LIST
    public function index()
    {
        $plans = $this->database->getReference('plans')->getValue() ?? [];
        return view('user.plans', compact('plans'));
    }

    // COMPARE SELECTED PLANS
    public function compare(Request $request)
    {
        $selectedIds = $request->input('plans', []);

        if (empty($selectedIds)) {
            return view('user.compare-plans', ['plans' => []]);
        }

        $allPlans = $this->database->getReference('plans')->getValue() ?? [];
        $plans = [];

        foreach ($selectedIds as $id) {
            if (!isset($allPlans[$id])) continue;

            $summary = $allPlans[$id]['summary'] ?? [];

            $plans[] = [
                'name' => $allPlans[$id]['name'] ?? 'Unnamed Plan',
                'annual_limit' => $summary['annual_limit'] ?? '-',
                'lifetime_limit' => $summary['lifetime_limit'] ?? '-',
                'hospitalisation' => $summary['hospitalisation'] ?? '-',
                'benefits' => $summary['benefits'] ?? '',
                'entry_age' => $summary['eligibility']['entry_age'] ?? '-',
                'nationality' => $summary['eligibility']['nationality'] ?? '-',
                'coverage_age' => $summary['eligibility']['coverage_age'] ?? '-',
                'coverage_term' => $summary['eligibility']['coverage_term'] ?? '-',
            ];
        }

        return view('user.compare-plans', compact('plans'));
    }
}
