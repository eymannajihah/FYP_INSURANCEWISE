<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuoteAssignedMail;
use Kreait\Firebase\Contract\Storage as FirebaseStorage;
use Illuminate\Support\Facades\Storage;
use Google\Cloud\Storage\StorageClient;




class AdminController extends Controller
{
    protected $database;

   public function __construct()
{
    $credentialsPath = env('FIREBASE_CREDENTIALS');

    if (!$credentialsPath || !file_exists($credentialsPath)) {
        throw new \Exception("Firebase credentials not found at: {$credentialsPath}");
    }

    $firebaseFactory = (new \Kreait\Firebase\Factory())
        ->withServiceAccount($credentialsPath)
        ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

    $this->database = $firebaseFactory->createDatabase();
}

    private function checkAdmin()
    {
        $user = Session::get('firebase_user');
        if (!$user || $user['role'] !== 'admin') {
            return redirect()->route('login')->withErrors(['unauthorized' => 'Access denied. Admins only.']);
        }
        return $user;
    }

    public function dashboard()
{
    // Check if the user is admin
    $check = $this->checkAdmin();
    if ($check instanceof \Illuminate\Http\RedirectResponse) return $check;

    // Firebase
    $firebase = (new \Kreait\Firebase\Factory)
        ->withServiceAccount(config('firebase.credentials.file'))
        ->withDatabaseUri(config('firebase.database.url'));
    $database = $firebase->createDatabase();

    // Plans
    $plans = $database->getReference('plans')->getValue() ?? [];
    $planCount = count($plans);

    // Users
    $users = $database->getReference('users')->getValue() ?? [];
    $userCount = count($users);

    // Pending Quote Requests (unassigned and not deleted)
    $allQuotes = $database->getReference('quote_requests')->getValue() ?? [];
    $pendingQuotes = collect($allQuotes)
        ->reject(fn($item) => ($item['status'] ?? '') === 'deleted')
        ->filter(fn($item) => empty($item['assigned_to']))
        ->sortByDesc('created_at')
        ->toArray();
    $pendingCount = count($pendingQuotes);

    // Return view with data
    return view('admin.admin_dashboard', compact(
        'planCount',
        'userCount',
        'pendingCount',
        'pendingQuotes'
    ));
}


    public function managePlans()
    {
        $check = $this->checkAdmin();
        if ($check instanceof \Illuminate\Http\RedirectResponse) return $check;

        $plans = $this->database->getReference('plans')->getValue() ?? [];
        $plans = array_filter($plans, fn($plan) => is_array($plan) && isset($plan['name'], $plan['category']));

        return view('admin.manage_plans', compact('plans'));
    }

  public function addPlan(Request $request)
{
    $check = $this->checkAdmin();
    if ($check instanceof \Illuminate\Http\RedirectResponse) return $check;

    // Validate input
    $request->validate([
        'name' => 'required|string',
        'category' => 'required|string',
        'overview' => 'required|string',
        'features' => 'required|string',
        'eligibility' => 'required|string',
        'fees' => 'required|string',
        'pidm' => 'nullable|string',
        'cash_rewards' => 'nullable|string',
        'additional_benefits' => 'nullable|string',
        'disclaimers' => 'nullable|string',
        'highlight' => 'nullable|string',
        'banner_image' => 'nullable|image|max:2048',
        'brochure' => 'nullable|file|mimes:pdf',
        'annual_limit' => 'required|string',
        'lifetime_limit' => 'required|string',
        'hospitalisation' => 'required|string',
        'benefits' => 'required|string',
        'entry_age' => 'required|string',
        'nationality' => 'required|string',
        'coverage_age' => 'required|string',
        'coverage_term' => 'required|string',
    ]);

    // Initialize Firebase Storage client
    $storage = new \Google\Cloud\Storage\StorageClient([
        'keyFilePath' => env('FIREBASE_CREDENTIALS'),
    ]);
    $bucket = $storage->bucket('insurancewise-731f8.firebasestorage.app');

    $bannerUrl = null;
    if ($request->hasFile('banner_image')) {
        $bannerFile = $request->file('banner_image');
        $bannerFilename = time() . '_' . $bannerFile->getClientOriginalName();

        // Upload banner to Firebase
        $bucket->upload(
            fopen($bannerFile->getRealPath(), 'r'),
            [
                'name' => 'plans/' . $bannerFilename,
                'predefinedAcl' => 'publicRead',
            ]
        );

        $bannerUrl = 'https://storage.googleapis.com/' . $bucket->name() . '/plans/' . $bannerFilename;
    }

    $brochureUrl = null;
    if ($request->hasFile('brochure')) {
        $brochureFile = $request->file('brochure');
        $brochureFilename = time() . '_' . $brochureFile->getClientOriginalName();

        // Upload brochure to Firebase
        $bucket->upload(
            fopen($brochureFile->getRealPath(), 'r'),
            [
                'name' => 'brochures/' . $brochureFilename,
                'predefinedAcl' => 'publicRead',
            ]
        );

        $brochureUrl = 'https://storage.googleapis.com/' . $bucket->name() . '/brochures/' . $brochureFilename;
    }

    // Save plan in Firebase Realtime Database
    $this->database->getReference('plans')->push([
        'name' => $request->name,
        'category' => $request->category,
        'overview' => $request->overview,
        'features' => $request->features,
        'eligibility' => $request->eligibility,
        'fees' => $request->fees,
        'pidm' => $request->pidm,
        'cash_rewards' => $request->cash_rewards,
        'additional_benefits' => $request->additional_benefits,
        'disclaimers' => $request->disclaimers,
        'highlight' => $request->highlight,
        'banner_image' => $bannerUrl,
        'brochure' => $brochureUrl,
        'summary' => [
            'annual_limit' => $request->annual_limit,
            'lifetime_limit' => $request->lifetime_limit,
            'hospitalisation' => $request->hospitalisation,
            'benefits' => $request->benefits,
            'eligibility' => [
                'entry_age' => $request->entry_age,
                'nationality' => $request->nationality,
                'coverage_age' => $request->coverage_age,
                'coverage_term' => $request->coverage_term,
            ],
        ],
    ]);

    return redirect()->route('admin.manage-plans')->with('success', 'Plan added successfully!');
}

    public function editPlanForm($id)
    {
        $check = $this->checkAdmin();
        if ($check instanceof \Illuminate\Http\RedirectResponse) return $check;

        $plan = $this->database->getReference("plans/{$id}")->getValue();
        if (!is_array($plan)) {
            return redirect()->route('admin.manage-plans')->withErrors(['invalid' => 'Plan not found.']);
        }

        return view('admin.edit_plan', compact('plan', 'id'));
    }

public function editPlan(Request $request, $id)
{
    $check = $this->checkAdmin();
    if ($check instanceof \Illuminate\Http\RedirectResponse) return $check;

    // Get the plan
    $plan = $this->database->getReference("plans/{$id}")->getValue();
    if (!is_array($plan)) {
        return redirect()->route('admin.manage-plans')->withErrors(['invalid' => 'Plan not found.']);
    }

    // Validate request
    $request->validate([
        'name' => 'required|string',
        'category' => 'required|string',
        'overview' => 'required|string',
        'features' => 'required|string',
        'eligibility' => 'required|string',
        'fees' => 'required|string',
        'pidm' => 'nullable|string',
        'cash_rewards' => 'nullable|string',
        'additional_benefits' => 'nullable|string',
        'disclaimers' => 'nullable|string',
        'highlight' => 'nullable|string',
        'annual_limit' => 'required|string',
        'lifetime_limit' => 'required|string',
        'hospitalisation' => 'required|string',
        'benefits' => 'required|string',
        'entry_age' => 'required|string',
        'nationality' => 'required|string',
        'coverage_age' => 'required|string',
        'coverage_term' => 'required|string',
        'banner_image' => 'nullable|image|max:2048',
        'brochure' => 'nullable|file|mimes:pdf',
        'delete_banner' => 'nullable|boolean',
        'delete_brochure' => 'nullable|boolean',
    ]);

    // Initialize Firebase Storage client
    $storage = new \Google\Cloud\Storage\StorageClient([
        'keyFilePath' => env('FIREBASE_CREDENTIALS'),
    ]);
    $bucket = $storage->bucket('insurancewise-731f8.firebasestorage.app');

    $updateData = [
        'name' => $request->name,
        'category' => $request->category,
        'overview' => $request->overview,
        'features' => $request->features,
        'eligibility' => $request->eligibility,
        'fees' => $request->fees,
        'pidm' => $request->pidm,
        'cash_rewards' => $request->cash_rewards,
        'additional_benefits' => $request->additional_benefits,
        'disclaimers' => $request->disclaimers,
        'highlight' => $request->highlight,
        'summary' => [
            'annual_limit' => $request->annual_limit,
            'lifetime_limit' => $request->lifetime_limit,
            'hospitalisation' => $request->hospitalisation,
            'benefits' => $request->benefits,
            'eligibility' => [
                'entry_age' => $request->entry_age,
                'nationality' => $request->nationality,
                'coverage_age' => $request->coverage_age,
                'coverage_term' => $request->coverage_term,
            ],
        ],
    ];

    // Handle banner image
    if ($request->hasFile('banner_image')) {
        $bannerFile = $request->file('banner_image');
        $bannerFilename = time() . '_' . $bannerFile->getClientOriginalName();

        // Upload to Firebase Storage
        $bucket->upload(
            fopen($bannerFile->getRealPath(), 'r'),
            [
                'name' => 'plans/' . $bannerFilename,
                'predefinedAcl' => 'publicRead',
            ]
        );

        $updateData['banner_image'] = 'https://storage.googleapis.com/' . $bucket->name() . '/plans/' . $bannerFilename;

    } elseif ($request->delete_banner) {
        // If admin wants to delete banner, remove old URL
        $updateData['banner_image'] = null;
    } else {
        // Keep existing banner
        $updateData['banner_image'] = $plan['banner_image'] ?? null;
    }

    // Handle brochure (same as before)
    if ($request->hasFile('brochure')) {
        $brochureFile = $request->file('brochure');
        $brochureFilename = time() . '_' . $brochureFile->getClientOriginalName();

        $bucket->upload(
            fopen($brochureFile->getRealPath(), 'r'),
            [
                'name' => 'brochures/' . $brochureFilename,
                'predefinedAcl' => 'publicRead',
            ]
        );

        $updateData['brochure'] = 'https://storage.googleapis.com/' . $bucket->name() . '/brochures/' . $brochureFilename;

    } elseif ($request->delete_brochure) {
        $updateData['brochure'] = null;
    } else {
        $updateData['brochure'] = $plan['brochure'] ?? null;
    }

    // Update plan in Firebase Realtime Database
    $this->database->getReference("plans/{$id}")->update($updateData);

    return redirect()->route('admin.manage-plans')->with('success', 'Plan updated successfully!');
}


    public function deletePlan($id)
    {
        $check = $this->checkAdmin();
        if ($check instanceof \Illuminate\Http\RedirectResponse) return $check;

        $this->database->getReference("plans/{$id}")->remove();

        return redirect()->route('admin.manage-plans')->with('success', 'Plan deleted successfully!');
    }

    public function assignQuote(Request $request, $id)
{
    $check = $this->checkAdmin();
    if ($check instanceof \Illuminate\Http\RedirectResponse) return $check;

    $request->validate([
        'assigned_to' => 'required|string'
    ]);

    // Get quote request from Firebase
    $quoteRef = $this->database->getReference("quote_requests/{$id}");
    $quote = $quoteRef->getValue();

    if (!$quote) {
        return redirect()->back()->withErrors(['not_found' => 'Quote request not found.']);
    }

    // Update assignment + status
    $quoteRef->update([
        'assigned_to' => $request->assigned_to,
        'status' => 'assigned'
    ]);

    // ✅ Send email to user
    Mail::to($quote['email'])->send(
        new QuoteAssignedMail(
            $quote['name'],
            $quote['phone'],
            $request->assigned_to
        )
    );

    return redirect()->back()->with('success', 'Staff assigned and email sent to user.');
}

}
