<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use Carbon\Carbon;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'plan_id' => Plan::factory(),
            'type' => 'trial',
            'status' => 'active',
            'trial_ends_at' => Carbon::now()->addDays(7),
        ];
    }
}