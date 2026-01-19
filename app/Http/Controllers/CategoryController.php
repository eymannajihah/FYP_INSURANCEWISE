<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    protected $database;

    public function __construct()
    {
        $credentialsPath = env('FIREBASE_CREDENTIALS');

        $firebase = (new Factory)
            ->withServiceAccount($credentialsPath)
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $this->database = $firebase->createDatabase();
    }

    /**
     * Normalize a stored path (from Firebase) into a public URL.
     * Supports:
     *  - storage paths created by $request->file()->store(...) (e.g. "banners/xxx.jpg")
     *  - local public image filenames (e.g. "medactive.jpg" or "image/medactive.jpg")
     *  - already full URLs (http(s)://...)
     */
    private function toPublicUrl(?string $path)
{
    if (empty($path)) return null;

    // Already a full URL
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }

    // public image folder
    $filename = basename($path);
    if (file_exists(public_path("image/{$filename}"))) {
        return asset("image/{$filename}");
    }

    // fallback
    return asset('image/default.jpg');
}


    private function toPoints($value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map(fn($v) => trim($v), $value)));
        }

        if (!is_string($value) || trim($value) === '') {
            return [];
        }

        // Split on newlines OR on periods followed by space OR on "•" bullets
        $parts = preg_split("/\r\n|\r|\n|[.]\s+|•/", $value);
        $parts = array_map(fn($p) => trim($p), $parts);
        $parts = array_values(array_filter($parts, fn($p) => $p !== ''));

        return $parts;
    }

    public function showCategory($category)
    {
        $raw = $this->database->getReference('plans')->getValue() ?? [];

        // Filter only plans matching the category (case-insensitive)
        $plans = array_filter($raw, function ($plan) use ($category) {
            return isset($plan['category']) && strtolower($plan['category']) === strtolower($category);
        });

        // Normalize each plan: image_url, brochure_url and convert features if needed
        $normalized = [];
        foreach ($plans as $id => $plan) {
            $p = is_array($plan) ? $plan : [];

            // derive image URL
            $imgPathCandidates = $p['banner_image'] ?? ($p['image_url'] ?? ($p['image'] ?? null));
            $p['image_url'] = $this->toPublicUrl($imgPathCandidates) ?? asset('image/default.jpg');

            // brochure
           // in showCategory() and viewPlan()
$p['brochure_url'] = $p['brochure'] ?? null; // already full URL


            // short_description fallback
            $p['short_description'] = $p['highlight'] ?? $p['subtitle'] ?? ($p['overview'] ?? null);

            // Convert features to array of points for listing preview if admin saved as string
            if (isset($p['features']) && !is_array($p['features'])) {
                $p['features'] = $this->toPoints($p['features']);
            }

            // keep other fields but normalize them into arrays of points where appropriate on detail page
            $normalized[$id] = $p;
        }

        return view('categories.category_plans', [
            'plans' => $normalized,
            'category' => $category,
        ]);
    }

    public function viewPlan($id)
    {
        $plan = $this->database->getReference("plans/{$id}")->getValue();

        if (!is_array($plan)) {
            abort(404, 'Plan not found.');
        }

        // normalize fields for the view
        $p = $plan;

        $imgPathCandidates = $p['banner_image'] ?? ($p['image_url'] ?? ($p['image'] ?? null));
        $p['image_url'] = $this->toPublicUrl($imgPathCandidates) ?? asset('image/default.jpg');

        $brochurePath = $p['brochure'] ?? ($p['brochure_url'] ?? null);
       // in showCategory() and viewPlan()
$p['brochure_url'] = $p['brochure'] ?? null; // already full URL


        // Convert textual fields into bullet arrays
        $p['overview_points'] = $this->toPoints($p['overview'] ?? '');
        $p['eligibility_points'] = $this->toPoints($p['eligibility'] ?? '');
        $p['fees_points'] = $this->toPoints($p['fees'] ?? '');
        $p['notes_points'] = $this->toPoints($p['disclaimers'] ?? ($p['notes'] ?? ''));
        // features may already be an array (preferred). If string, convert.
        if (isset($p['features'])) {
            $p['features_points'] = is_array($p['features']) ? array_values(array_filter(array_map('trim', $p['features']))) : $this->toPoints($p['features']);
        } else {
            $p['features_points'] = [];
        }

        // optional: pidm, cash_rewards, additional_benefits -> convert to points
        $p['pidm_points'] = $this->toPoints($p['pidm'] ?? '');
        $p['cash_rewards_points'] = $this->toPoints($p['cash_rewards'] ?? '');
        $p['additional_benefits_points'] = $this->toPoints($p['additional_benefits'] ?? '');

        return view('categories.view-plans', ['plan' => $p]);
    }

public function downloadBrochure($filename)
{
    $path = public_path("brochures/{$filename}");

    if (!file_exists($path)) {
        abort(404, 'Brochure not available.');
    }

    return response()->download($path, $filename);
}


}
