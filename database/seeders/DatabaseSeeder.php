<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Hotel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOnCreate(['email' => 'admin@test.com'], [
            'name'     => 'Admin',
            'password' => bcrypt('123456'),
            'role'     => 'admin',
        ]);

        $owner = User::updateOnCreate(['email' => 'owner@test.com'], [
            'name'     => 'Hotel Owner',
            'password' => bcrypt('123456'),
            'role'     => 'hotel_owner',
        ]);

        User::updateOnCreate(['email' => 'test@example.com'], [
            'name'     => 'Test User',
            'password' => bcrypt('123456'),
            'role'     => 'user',
        ]);

        $this->call(HotelSeeder::class);

        Hotel::first()?->update(['owner_id' => $owner->id]);
    }
}
