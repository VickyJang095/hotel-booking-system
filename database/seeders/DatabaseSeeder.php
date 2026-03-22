<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Hotel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name'     => 'Admin',
                'password' => bcrypt('123456'),
                'role'     => 'admin',
            ]
        );

        $owner = User::updateOrCreate(
            ['email' => 'owner@test.com'],
            [
                'name'     => 'Hotel Owner',
                'password' => bcrypt('123456'),
                'role'     => 'hotel_owner',
            ]
        );

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'     => 'Test User',
                'password' => bcrypt('123456'),
                'role'     => 'user',
            ]
        );

        // ── Hotels ───────────────────────────────────────────
        $this->call(HotelSeeder::class);

        // Gán hotel đầu tiên cho owner@test.com
        Hotel::first()?->update(['owner_id' => $owner->id]);
    }
}
