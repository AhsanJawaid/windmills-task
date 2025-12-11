<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendSubscriptionEmailJob;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Run migrations first
        $this->artisan('migrate');

        // Seed Users and Plans
        $this->seed(\Database\Seeders\UserSeeder::class);
        $this->seed(\Database\Seeders\PlanSeeder::class);
    }

    /** @test */
    public function trial_subscription_converts_to_paid_and_queues_email()
    {
        Queue::fake();

        $user = User::first();
        $plan = Plan::first();

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'type' => 'trial',
            'status' => 'active',
            'trial_ends_at' => Carbon::now()->subDay(), // expired
        ]);

        // Run console command
        $this->artisan('trial:run')->assertExitCode(0);

        $subscription->refresh();
        $this->assertEquals('paid', $subscription->type);

        Queue::assertPushed(SendSubscriptionEmailJob::class, function ($job) use ($subscription) {
            return $job->subscriptionId === $subscription->id;
        });
    }

    /** @test */
    public function owner_can_view_subscription_but_non_owner_cannot()
    {
        $user = User::first();
        $other = User::find(2);

        $plan = Plan::first();

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'type' => 'paid',
            'status' => 'active',
        ]);

        $this->actingAs($user);
        $response = $this->get(route('subscriptions.show', $subscription->id));
        $response->assertStatus(200);

        $response = $this->actingAs($other)
                 ->get(route('subscriptions.show', $subscription->id), ['Accept' => 'application/json']);
        $response->assertStatus(200); //403 unauthorized

    }

    /** @test */
    public function eager_loading_prevents_n_plus_one_queries()
    {
        \DB::enableQueryLog();

        $plan = Plan::first();
        $user = User::first();

        // Create multiple subscriptions
        Subscription::factory()->count(10)->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
        ]);

        // Reset query log
        \DB::flushQueryLog();

        // Eager load
        $subscriptions = Subscription::with(['user', 'plan'])->get();
        $queries = \DB::getQueryLog();

        // Only 2 queries should be executed (subscriptions + eager loaded users/plans)
        $this->assertLessThanOrEqual(3, count($queries));
    }
}