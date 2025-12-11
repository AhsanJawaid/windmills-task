<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;

Route::prefix('subscriptions')->group(function () {
    Route::get('/', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/{id}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
});