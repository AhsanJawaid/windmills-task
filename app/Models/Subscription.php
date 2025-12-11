<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'type',
        'trial_ends_at',
    ];

    protected $dates = [
        'trial_ends_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isTrialExpired(): bool
    {
        return $this->type === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isPast();
    }
}