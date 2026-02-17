<?php

namespace Database\Seeders;

use App\Models\Bookings\BookingStatus;
use Illuminate\Database\Seeder;

class BookingStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Pending', 'color' => 'yellow'],
            ['name' => 'Confirmed', 'color' => 'blue'],
            ['name' => 'Checked In', 'color' => 'green'],
            ['name' => 'Checked Out', 'color' => 'gray'],
            ['name' => 'Cancelled', 'color' => 'red'],
            ['name' => 'No Show', 'color' => 'orange'],
        ];

        foreach ($statuses as $status) {
            BookingStatus::create($status);
        }
    }
}
