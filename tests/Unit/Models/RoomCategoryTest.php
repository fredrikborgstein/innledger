<?php

namespace Tests\Unit\Models;

use App\Models\Rooms\Attribute;
use App\Models\Rooms\Room;
use App\Models\Rooms\RoomCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_category_has_fillable_attributes(): void
    {
        $category = RoomCategory::create([
            'code' => 'STD',
            'name' => 'Standard Room',
            'description' => 'Basic room',
        ]);

        $this->assertEquals('STD', $category->code);
        $this->assertEquals('Standard Room', $category->name);
        $this->assertEquals('Basic room', $category->description);
    }

    public function test_room_category_has_many_rooms(): void
    {
        $category = RoomCategory::factory()->create();
        $room = Room::factory()->create(['room_category_id' => $category->id]);

        $this->assertTrue($category->rooms->contains($room));
    }

    public function test_room_category_belongs_to_many_attributes(): void
    {
        $category = RoomCategory::factory()->create();
        $attribute = Attribute::factory()->create();

        $category->attributes()->attach($attribute->id);

        $this->assertTrue($category->attributes->contains($attribute));
    }
}
