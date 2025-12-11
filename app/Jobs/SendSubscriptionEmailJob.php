<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSubscriptionEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $userId;
    public int $subscriptionId;
    public string $subject;
    public string $body;

    public function __construct(int $userId, int $subscriptionId, string $subject, string $body)
    {
        $this->userId = $userId;
        $this->subscriptionId = $subscriptionId;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function handle(): void
    {
        Log::info("Email to user {$this->userId} about subscription {$this->subscriptionId}: {$this->subject}");
        // Implement Mail::to($user)->send(new SubscriptionMail(...)) here
    }
}