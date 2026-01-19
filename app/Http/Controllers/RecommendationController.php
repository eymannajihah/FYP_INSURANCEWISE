<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function index()
    {
        return view('user.recommendationform');
    }

    public function submit(Request $request)
{
    // Validate input
    $data = $request->validate([
        'name' => 'required|string',
        'age' => 'required|integer',
        'goal' => 'required|string',
        'coverage' => 'required|string',
        'medical_concern' => 'required|string',
        'lifestyle' => 'required|string',
        'dependents' => 'required|string',
        'salary' => 'required|integer',
        'budget' => 'required|string',
        'insurance' => 'required|string',
        'gender' => 'nullable|string',
        'health_status' => 'required|string'
    ]);

    // Initialize plan categories
    $plans = [
        'Medical' => [],
        'Critical' => [],
        'Life' => [],
    ];

    // ===== First Layer: Budget & Salary =====
    if ($data['salary'] < 2500 || $data['budget'] === 'low') {
        $plans['Life'][] = 'PRUTerm';
    } elseif ($data['budget'] === 'medium' && $data['salary'] < 5000) {
        $plans['Life'][] = 'PRUWith You Plus';
    } elseif ($data['budget'] === 'high' && $data['salary'] >= 5000) {
        $plans['Life'][] = 'PRUWith You Plus';
    }

    // ===== Second Layer: Goal =====
    switch ($data['goal']) {
        case 'critical_illness':
            $plans['Critical'][] = 'PRUMy Critical Care';
            break;

        case 'medical':
    // ===== Medical Goal Handling =====
    if ($data['lifestyle'] === 'active') {
        // Most suitable for active lifestyle
        $plans['Medical'] = ['PRUMillion Med Active'];
    } else {
        // Default medical plan
        $plans['Medical'][] = 'PRUMillion Med';
        
        // Long-term coverage adjustment
        if ($data['coverage'] === 'lifetime' && $data['salary'] >= 3000) {
            $plans['Medical'][] = 'PRUValue Med';
        }
    }
    break;
        case 'savings':
        case 'protection':
    if ($data['salary'] >= 2500 && $data['budget'] !== 'low') {
        $plans['Life'][] = 'PRUWith You Plus';
    }
    break;

    }

    // ===== Gender-Based Add-On for Critical Care =====
    if (in_array('PRUMy Critical Care', $plans['Critical'])) {
        if ($data['gender'] === 'female') $plans['Critical'][] = 'PRULady';
        if ($data['gender'] === 'male') $plans['Critical'][] = 'PRUMan';
    }
    
// ===== Health & Long-term Income Protection =====
if (
    in_array($data['goal'], ['protection', 'savings']) && // User wants protection or savings
    $data['health_status'] !== 'good' &&                  // Health is not perfect
    $data['salary'] >= 3000 &&                            // Salary sufficient
    $data['coverage'] === 'lifetime'                      // Wants lifetime coverage
) {
    // Add PRULive Well at the beginning of Life plans to make it most recommended
    array_unshift($plans['Life'], 'PRULive Well');
}

    // ===== Remove duplicates =====
    foreach ($plans as $category => $list) {
        $plans[$category] = array_unique($list);
    }

    // ===== Only show plans relevant to the user's goal =====
    if ($data['goal'] === 'medical') {
        $plansToShow = $plans['Medical'];
    } elseif ($data['goal'] === 'critical_illness') {
        $plansToShow = $plans['Critical'];
    } else { // savings/protection
        $plansToShow = $plans['Life'];
    }

    // ===== Plan details =====
    $details = [
        'PRUTerm' => ['price' => 'RM100/month (est.)', 'desc' => 'Simple term life protection for you and your family, providing coverage in case of untimely events.'],
        'PRUWith You Plus' => ['price' => 'Varies by age & selected options', 'desc' => 'Flexible investment-linked insurance for lifetime protection and long-term savings.Flexible investment-linked plan that grows your savings while providing lifetime protection for you and your loved ones.'],
        'PRUMy Critical Care' => ['price' => 'Varies — contact us for a personalized quote', 'desc' => 'Protect yourself against 160 critical illnesses with multiple claim options and recovery support.'],
        'PRULady' => ['price' => 'Varies — contact us for a personalised quote', 'desc' => 'Comprehensive protection for women, covering female-specific illnesses, maternity, and recovery benefits.'],
        'PRUMan' => ['price' => 'Varies — contact us for a personalised quote', 'desc' => 'Comprehensive protection for men, covering male-specific illnesses and critical health conditions.'],
        'PRUMillion Med' => ['price' => 'Price depends on plan & deductible', 'desc' => 'Hospital and surgical coverage with cashless admission at selected hospitals and flexible benefit options.'],
        'PRUMillion Med Active' => ['price' => 'Price depends on plan & deductible', 'desc' => 'Ideal for active lifestyles: hospital coverage with added flexibility for day-to-day medical needs.'],
        'PRUValue Med' => ['price' => 'Price depends on age & selected options', 'desc' => 'Long-term hospitalisation and surgical plan with high coverage limits and optional add-ons for peace of mind.'],
        'PRULive Well' => ['price' => 'Varies by options selected', 'desc' => 'Investment-linked life plan with wellness rewards, promoting healthier lifestyle and financial protection.'],
    ];

    // Return to Blade view
    return view('user.recommendationresult', compact('plansToShow', 'data', 'details'));
}

}
