<?php

namespace Database\Factories\Bookings;

use App\Models\Bookings\Booking;
use App\Models\Bookings\BookingStatus;
use App\Models\Bookings\Guest;
use App\Models\Rooms\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bookings\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $checkIn = fake()->dateTimeBetween('now', '+1 month');
        $checkOut = fake()->dateTimeBetween($checkIn, '+2 months');

        return [
            'booking_number' => Booking::generateBookingNumber(),
            'guest_id' => Guest::factory(),
            'room_id' => Room::factory(),
            'booking_status_id' => BookingStatus::factory(),
            'created_by' => User::factory(),
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'number_of_adults' => fake()->numberBetween(1, 4),
            'number_of_children' => fake()->numberBetween(0, 2),
            'total_price' => fake()->randomFloat(2, 50, 500),
            'special_requests' => null,
            'notes' => null,
        ];
    }
}
