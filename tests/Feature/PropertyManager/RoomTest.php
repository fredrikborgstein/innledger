<?php

namespace Tests\Feature\PropertyManager;

use App\Models\Rooms\Room;
use App\Models\Rooms\RoomCategory;
use App\Models\Rooms\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(['property_manager' => true]));
    }

    public function test_can_view_rooms_page(): void
    {
        $response = $this->get(route('property-manager.rooms.index'));

        $response->assertStatus(200);
        $response->assertSeeLivewire('property-manager.rooms.index');
    }

    public function test_can_list_rooms(): void
    {
        $rooms = Room::factory()->count(3)->create();

        Livewire::test('property-manager.rooms.index')
            ->assertSee($rooms[0]->room_number)
            ->assertSee($rooms[1]->room_number)
            ->assertSee($rooms[2]->room_number);
    }

    public function test_can_create_room(): void
    {
        $category = RoomCategory::factory()->create();
        $status = Status::factory()->create();

        Livewire::test('property-manager.rooms.index')
            ->callTableAction('create', data: [
                'room_number' => '999',
                'room_category_id' => $category->id,
                'status_id' => $status->id,
            ]);

        $this->assertDatabaseHas('rooms', [
            'room_number' => '999',
            'room_category_id' => $category->id,
            'status_id' => $status->id,
        ]);
    }

    public function test_can_edit_room(): void
    {
        $room = Room::factory()->create();
        $newStatus = Status::factory()->create();

        Livewire::test('property-manager.rooms.index')
            ->callTableAction('edit', $room, data: [
                'room_number' => $room->room_number,
                'room_category_id' => $room->room_category_id,
                'status_id' => $newStatus->id,
            ]);

        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'status_id' => $newStatus->id,
        ]);
    }

    public function test_can_delete_room(): void
    {
        $room = Room::factory()->create();

        Livewire::test('property-manager.rooms.index')
            ->callTableAction('delete', $room);

        $this->assertDatabaseMissing('rooms', [
            'id' => $room->id,
        ]);
    }
}
