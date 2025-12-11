<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Jobs\SendSubscriptionEmailJob;
use Carbon\Carbon;

class RunTrialJob extends Command
{
    protected $signature = 'trial:run';
    protected $description = 'Process expired trials, convert to paid, and queue subscription emails';

    public function handle(): void
    {
        $this->info('Starting trial job...');

        $expiredTrials = Subscription::where('type', 'trial')
            ->where('status', 'active')
            ->where('trial_ends_at', '<', Carbon::now())
            ->get();

        foreach ($expiredTrials as $subscription) {
            if ($subscription->status === 'cancelled') continue;

            $subscription->type = 'paid';
            $subscription->save();

            SendSubscriptionEmailJob::dispatch(
                $subscription->user_id,
                $subscription->id,
                'Your subscription has been updated',
                'Your trial has ended, and your subscription is now active.'
            );

            $this->info("Processed subscription ID: {$subscription->id}");
        }

        $this->info('Trial job completed.');
    }
}