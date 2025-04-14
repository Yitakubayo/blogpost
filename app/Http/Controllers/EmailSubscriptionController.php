<?php

namespace App\Http\Controllers;

use App\Models\EmailSubscription;
use Illuminate\Http\Request;

class EmailSubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:email_subscriptions,email'
        ]);

        EmailSubscription::create($validated);

        return back()->with('success', 'You have successfully subscribed to our newsletter!');
    }

    public function unsubscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:email_subscriptions,email'
        ]);

        $subscription = EmailSubscription::where('email', $validated['email'])->first();
        $subscription->update(['is_active' => false]);

        return back()->with('success', 'You have successfully unsubscribed from our newsletter.');
    }
} 