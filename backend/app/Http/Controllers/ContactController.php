<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Submit a new lead or quote request.
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'organisation' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'service_category' => 'required|string|in:Hardware,IT Digital Services',
            'service_offering' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
        ]);

        $contact = Contact::create($validated);

        // In a real application, we would trigger an email notification here.
        // e.g., Mail::to('support@enterpriseit.com.au')->send(new QuoteRequested($contact));
        Log::info("Lead Generation Submission Saved: ID {$contact->id} - Service: {$contact->service_offering}");

        return response()->json([
            'success' => true,
            'message' => 'An enterprise IT representative will contact you within 24 hours.',
            'data' => $contact
        ], 201);
    }
}
