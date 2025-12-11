<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use Carbon\Carbon;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $plans = Plan::all();

        foreach ($users as $user) {
            foreach ($plans as $plan) {
                Subscription::factory()->create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'type' => 'paid',
                    'status' => 'active',
                    'trial_ends_at' => Carbon::now()->subDays(rand(1, 30)), // if needed for trial
                ]);
            }
        }
    }
}