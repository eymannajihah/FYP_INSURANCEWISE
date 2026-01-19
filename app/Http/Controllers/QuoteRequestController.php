<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use App\Mail\QuoteAssignedMail;

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

    /**
     * Store a new quote request (PUBLIC FORM)
     */
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
        } catch (\Throwable $e) {
            Log::error('Quote store failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error'   => 'Failed to submit quote request.'
            ], 500);
        }
    }

    /**
     * Admin – Pending quote requests
     */
    public function index()
    {
        $allRequests = $this->database
            ->getReference('quote_requests')
            ->getValue() ?? [];

        $requests = collect($allRequests)
            ->reject(fn ($item) =>
                in_array($item['status'] ?? '', ['assigned', 'deleted'])
            )
            ->sortByDesc('created_at')
            ->toArray();

        return view('admin.quote_assignment', compact('requests'));
    }

    /**
     * Admin – Assigned quotes
     */
    public function assigned()
    {
        $allRequests = $this->database
            ->getReference('quote_requests')
            ->getValue() ?? [];

        $assigned = collect($allRequests)
            ->filter(fn ($item) =>
                ($item['status'] ?? '') === 'assigned'
            )
            ->sortByDesc('assigned_at')
            ->toArray();

        return view('admin.quote_assigned', compact('assigned'));
    }

    /**
     * Admin – Assign staff to quote (AJAX)
     */
    public function assign(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|string|max:255',
        ]);

        $ref   = $this->database->getReference("quote_requests/{$id}");
        $quote = $ref->getValue();

        if (!$quote) {
            return response()->json([
                'success' => false,
                'error'   => 'Quote request not found.'
            ], 404);
        }

        // ✅ Update Firebase FIRST (fast)
        $ref->update([
            'assigned_to' => $request->assigned_to,
            'status'      => 'assigned',
            'assigned_at' => now()->toDateTimeString(),
            'updated_at'  => now()->toDateTimeString(),
        ]);

        /**
         * ✅ Send email AFTER response
         * ❌ No dispatch()
         * ❌ No closure serialization
         * ❌ No CurlHandle issues
         */
        App::terminating(function () use ($quote, $request) {
            try {
                Mail::to($quote['email'])->send(
                    new QuoteAssignedMail(
                        $quote['name'],
                        $quote['phone'],
                        $request->assigned_to
                    )
                );
            } catch (\Throwable $e) {
                Log::error('Quote assignment email failed', [
                    'email' => $quote['email'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ]);
            }
        });

        // ✅ CLEAN JSON RESPONSE (no corruption)
        return response()->json([
            'success' => true,
            'message' => 'Staff assigned successfully. Email will be sent shortly.'
        ]);
    }

    /**
     * Admin – Archive (soft delete)
     */
    public function destroy($id)
    {
        $ref = $this->database->getReference("quote_requests/{$id}");

        if (!$ref->getSnapshot()->exists()) {
            return redirect()
                ->back()
                ->withErrors(['not_found' => 'Quote request not found.']);
        }

        $ref->update([
            'status'     => 'deleted',
            'deleted_at' => now()->toDateTimeString(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Quote request archived successfully.');
    }
}
