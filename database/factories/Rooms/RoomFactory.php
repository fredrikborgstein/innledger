<?php

namespace Database\Factories\Rooms;

use App\Models\Rooms\Room;
use App\Models\Rooms\RoomCategory;
use App\Models\Rooms\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'room_number' => fake()->unique()->numerify('###'),
            'room_category_id' => RoomCategory::factory(),
            'status_id' => Status::factory(),
        ];
    }
}
