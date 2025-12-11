# Windmills Subscription Project - Laravel 12

## Overview

This module is a full refactor of the previous Yii2 subscription system into Laravel 12. It provides:

- Subscription management (list, view)
- Trial subscriptions with automatic conversion to paid
- Email notifications via queue jobs
- RBAC enforcement (owner/admin)
- Zero-downtime migrations
- Performance-optimized queries (eager loading)

---

## Architecture Decisions

- **Models:** `User`, `Plan`, `Subscription` — all DB logic resides here.
- **Controllers:** `SubscriptionController` only handles HTTP request/response.
- **Views:** Blade templates render data; no raw SQL.
- **Jobs:** `SendSubscriptionEmailJob` handles queued emails asynchronously.
- **Console Command:** `RunTrialJob` detects expired trials, converts them to paid, and queues emails.
- **Security:** `SubscriptionPolicy` enforces owner/admin access.
- **Performance:** Eager loading used in index/list pages to prevent N+1 queries.

---

## Installation & Setup

1. Clone this repo to your local machine:  
```bash
git clone <repo-url>
cd <project-folder>
```
2. Install dependencies using composer:
```bash
composer install
```
3. Copy .env.example to .env and update your database credentials:
```bash
cp .env.example .env
```
4. Generate app key:
```bash
php artisan key:generate
```
5. Run migrations and seed database:
```bash
php artisan migrate --seed
```
6. Start the local server:
```bash
php artisan serve
```

## How to Check Trial Subscription

When a new subscription is created as type trial, it will automatically set trial_ends_at 7 days from creation.

Daily, a console command checks for expired trials and converts them to paid if not cancelled.

It also queues a SendSubscriptionEmailJob for notification.

To run the trial conversion manually:
```bash
php artisan trial:run
```
After this, check the subscription table, expired trials should now have type = paid.

## Trial Subscription Flow
```bash
[New Trial Subscription] 
          |
          v
[trial_ends_at = 7 days from now]
          |
          v
[Console Command runs daily]
          |
          v
[Check expired trials]
          |
          +--> If cancelled --> keep as cancelled
          |
          +--> If not cancelled --> convert to paid
                                      |
                                      v
                         [Queue SendSubscriptionEmailJob]
```

## RBAC-Based Access Control

We implemented Role-Based Access Control using a is_admin field on users.
- Admins can view all subscriptions.
- Owners can only view their own subscriptions.
- Any other user trying to access a subscription will get 403 Unauthorized.
- Implemented using Laravel Policies:
```bash
$this->authorize('view', $subscription);
```

## Idempotent Migrations
- All migrations are safe to run multiple times.
- Created missing columns, indexes, foreign keys in a way that wont break if table already exists.
- Example: subscriptions table migration checks if columns exists before adding.

## Performance Optimization
- Original Yii2 code had N+1 query problem when fetching subscriptions with user and plan.
- Fixed using Eager Loading in Laravel:
```bash
$subscriptions = Subscription::with(['user', 'plan'])->get();
```
- Now the number of queries stays constant regardless of how many subscriptions are displayed.

## Testing
- We use MySQL for unit tests.
- Seeders populate users, plans, subscriptions for testing.
- PHPUnit tests cover:
  - Trial conversion to paid
  - Owner/admin RBAC checks
  - N+1 query prevention

## Run tests:
```bash   
php artisan test
```
