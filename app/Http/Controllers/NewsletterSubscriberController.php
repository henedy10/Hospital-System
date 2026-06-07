<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsletterSubscriberController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
        ]);

        \App\Models\NewsletterSubscriber::create([
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'Thank you for subscribing to our newsletter!');
    }
}
