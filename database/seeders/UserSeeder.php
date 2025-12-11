<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'id' => 1,
            'username' => 'alice',
            'is_admin' => 0,
        ]);

        User::factory()->create([
            'id' => 2,
            'username' => 'bob',
            'is_admin' => 1,
        ]);

        User::factory()->create([
            'id' => 3,
            'username' => 'charlie',
            'is_admin' => 0,
        ]);
    }
}