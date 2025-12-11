<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::factory()->create(['name' => 'Basic', 'price' => 9.99]);
        Plan::factory()->create(['name' => 'Pro', 'price' => 19.99]);
        Plan::factory()->create(['name' => 'Enterprise', 'price' => 99.00]);
    }
}