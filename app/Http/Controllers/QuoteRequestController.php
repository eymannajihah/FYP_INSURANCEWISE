<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuoteAssignedMail;
use Illuminate\Support\Facades\Log;

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

    // Store a new quote request
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        try {
            $this->database->getReference('quote_requests')->push([
                'name'       => $request->name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'status'     => 'pending',
                'created_at' => now()->toDateTimeString(),
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    // Pending quotes page
    public function index()
    {
        $allRequests = $this->database->getReference('quote_requests')->getValue() ?? [];

        $requests = collect($allRequests)
            ->reject(fn($item) => ($item['status'] ?? '') === 'deleted' || ($item['status'] ?? '') === 'assigned')
            ->sortByDesc('created_at')
            ->toArray();

        return view('admin.quote_assignment', compact('requests'));
    }

    // Assigned quotes page
    public function assigned()
    {
        $allRequests = $this->database->getReference('quote_requests')->getValue() ?? [];

        $assigned = collect($allRequests)
            ->filter(fn($item) => isset($item['assigned_to']) && ($item['status'] ?? '') === 'assigned')
            ->sortByDesc('assigned_at')
            ->toArray();

        return view('admin.quote_assigned', compact('assigned'));
    }

    // Assign staff to a quote (AJAX POST)
   public function assign(Request $request, $id)
{
    $request->validate([
        'assigned_to' => 'required|string|max:255',
    ]);

    $ref = $this->database->getReference("quote_requests/{$id}");
    $quote = $ref->getValue();

    if (!$quote) {
        return response()->json([
            'success' => false,
            'error' => 'Quote request not found.'
        ], 404);
    }

    // 1️⃣ Update Firebase immediately
    $ref->update([
        'assigned_to' => $request->assigned_to,
        'status'      => 'assigned',
        'assigned_at' => now()->toDateTimeString(),
        'updated_at'  => now()->toDateTimeString(),
    ]);

    // 2️⃣ Queue the email (do NOT block assignment)
    try {
        Mail::to($quote['email'])->queue(
            new QuoteAssignedMail(
                $quote['name'],
                $quote['phone'],
                $request->assigned_to
            )
        );

        Log::info("Queued email to {$quote['email']} for assigned staff {$request->assigned_to}");
    } catch (\Exception $e) {
        Log::error("Failed to queue email to {$quote['email']}: ".$e->getMessage());
    }

    // 3️⃣ Respond immediately to frontend
    return response()->json([
        'success' => true,
        'message' => 'Staff assigned successfully. Email will be sent shortly.'
    ]);
}

    // Delete (archive) assigned quote
    public function destroy($id)
    {
        $ref = $this->database->getReference("quote_requests/{$id}");

        if (!$ref->getSnapshot()->exists()) {
            return redirect()->back()->withErrors(['not_found' => 'Quote request not found.']);
        }

        $ref->update([
            'status'     => 'deleted',
            'deleted_at' => now()->toDateTimeString(),
        ]);

        return redirect()->back()->with('success', 'Quote request archived successfully.');
    }
}
