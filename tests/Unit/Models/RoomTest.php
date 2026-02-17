<?php

namespace Tests\Unit\Models;

use App\Models\Rooms\Room;
use App\Models\Rooms\RoomCategory;
use App\Models\Rooms\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_has_fillable_attributes(): void
    {
        $category = RoomCategory::factory()->create();
        $status = Status::factory()->create();

        $room = Room::create([
            'room_number' => '101',
            'room_category_id' => $category->id,
            'status_id' => $status->id,
        ]);

        $this->assertEquals('101', $room->room_number);
        $this->assertEquals($category->id, $room->room_category_id);
        $this->assertEquals($status->id, $room->status_id);
    }

    public function test_room_belongs_to_room_category(): void
    {
        $category = RoomCategory::factory()->create();
        $room = Room::factory()->create(['room_category_id' => $category->id]);

        $this->assertInstanceOf(RoomCategory::class, $room->roomCategory);
        $this->assertEquals($category->id, $room->roomCategory->id);
    }

    public function test_room_belongs_to_status(): void
    {
        $status = Status::factory()->create();
        $room = Room::factory()->create(['status_id' => $status->id]);

        $this->assertInstanceOf(Status::class, $room->status);
        $this->assertEquals($status->id, $room->status->id);
    }
}
