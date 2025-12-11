@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Subscription Details</h1>
    <ul class="list-group">
        <li class="list-group-item"><strong>ID:</strong> {{ $subscription->id }}</li>
        <li class="list-group-item"><strong>User:</strong> {{ $subscription->user->username }}</li>
        <li class="list-group-item"><strong>Plan:</strong> {{ $subscription->plan->name }}</li>
        <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($subscription->status) }}</li>
        <li class="list-group-item"><strong>Type:</strong> {{ ucfirst($subscription->type) }}</li>
        <li class="list-group-item"><strong>Trial Ends At:</strong> {{ $subscription->trial_ends_at?->format('Y-m-d H:i') ?? '-' }}</li>
    </ul>
    <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection