<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Role::factory()->create([
            'name' => 'Property Manager',
            'description' => 'Property Manager role',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'property_manager' => true,
        ]);

        $this->call([
            RoomManagementSeeder::class,
            BookingStatusSeeder::class,
        ]);
    }
}
