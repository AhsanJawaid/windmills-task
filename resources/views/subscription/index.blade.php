@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Subscriptions</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Plan</th>
                <th>Status</th>
                <th>Type</th>
                <th>Trial Ends At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscriptions as $sub)
            <tr>
                <td>{{ $sub->id }}</td>
                <td>{{ $sub->user->username }}</td>
                <td>{{ $sub->plan->name }}</td>
                <td>{{ ucfirst($sub->status) }}</td>
                <td>{{ ucfirst($sub->type) }}</td>
                <td>{{ $sub->trial_ends_at?->format('Y-m-d H:i') ?? '-' }}</td>
                <td><a href="{{ route('subscriptions.show', $sub->id) }}" class="btn btn-primary btn-sm">View</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $subscriptions->links() }}
</div>
@endsection