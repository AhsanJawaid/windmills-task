<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['user', 'plan'])->paginate(10);
        return view('subscription.index', compact('subscriptions'));
    }

    public function show($id)
    {
        $subscription = Subscription::with(['user', 'plan'])->findOrFail($id);
        return view('subscription.view', compact('subscription'));
    }
}