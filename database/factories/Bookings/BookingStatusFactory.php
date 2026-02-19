<?php

namespace Database\Factories\Bookings;

use App\Models\Bookings\BookingStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bookings\BookingStatus>
 */
class BookingStatusFactory extends Factory
{
    protected $model = BookingStatus::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Pending', 'Confirmed', 'Checked In', 'Checked Out', 'Cancelled']),
            'color' => fake()->randomElement(['gray', 'blue', 'green', 'yellow', 'red']),
        ];
    }
}
