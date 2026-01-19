<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class QuoteRequestController extends Controller
{
    protected $database;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $this->database = $firebase->createDatabase();
    }

    // Store the quote request
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        try {
            $ref = $this->database->getReference('quote_requests')->push([
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status'=> 'pending',
                'created_at' => now()->toDateTimeString(),
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Admin: show all quote requests
 public function index()
{
    $allRequests = $this->database->getReference('quote_requests')->getValue() ?? [];

    // Only include requests that have not been assigned and are not deleted
    $requests = collect($allRequests)
        ->reject(function ($item) {
            return isset($item['status']) && $item['status'] === 'deleted';
        })
        ->filter(function ($item) {
            // Keep only unassigned
            return empty($item['assigned_to']);
        })
        ->sortByDesc('created_at') // latest submissions on top
        ->toArray();

    return view('admin.quote_assignment', compact('requests'));
}



    private function sortByLatest($data)
{
    if (!is_array($data)) return [];

    uasort($data, function ($a, $b) {
        return strtotime($b['created_at'] ?? '') <=> strtotime($a['created_at'] ?? '');
    });

    return $data;
}

public function assignedList()
{
    $allRequests = $this->database
        ->getReference('quote_requests')
        ->getValue() ?? [];

    // Filter only ASSIGNED
    $assigned = array_filter($allRequests, function ($req) {
        return ($req['status'] ?? '') === 'assigned';
    });

    $assigned = $this->sortByLatest($assigned);

    return view('admin.quote_assigned', compact('assigned'));
}

public function destroy($id)
{
    try {
        $ref = $this->database->getReference("quote_requests/$id");

        // Safety check: ensure record exists
        if (!$ref->getSnapshot()->exists()) {
            return back()->with('error', 'Quote request not found.');
        }

        // Soft delete
        $ref->update([
            'status' => 'deleted',
            'deleted_at' => now()->toDateTimeString(),
        ]);

        return back()->with('success', 'Quote request archived successfully.');
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to archive quote request.');
    }
}


public function assigned()
{
    $allRequests = $this->database->getReference('quote_requests')->getValue() ?? [];

    // Only include assigned requests and exclude deleted
    $assigned = collect($allRequests)
        ->filter(function ($item) {
            return isset($item['assigned_to']) && $item['status'] !== 'deleted';
        })
        ->sortByDesc('updated_at') // latest assigned first
        ->toArray();

    return view('admin.quote_assigned', compact('assigned'));
}

// Admin: assign staff to a quote request
public function assign(Request $request, $id)
{
    $request->validate([
        'assigned_to' => 'required|string|max:255',
    ]);

    $ref = $this->database->getReference("quote_requests/{$id}");
    $quote = $ref->getValue();

    if (!$quote) {
        return back()->with('error', 'Quote request not found.');
    }

    $ref->update([
        'assigned_to' => $request->assigned_to,
        'status' => 'assigned',
        'assigned_at' => now()->toDateTimeString(), // <-- THIS LINE IS CRUCIAL
        'updated_at' => now()->toDateTimeString(),
    ]);

    return redirect()->route('quote.assignment')->with('success', 'Quote request assigned successfully.');
}

}
