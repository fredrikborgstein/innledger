<?php

namespace Tests\Feature\PropertyManager;

use App\Models\Rooms\RoomCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RoomCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(['property_manager' => true]));
    }

    public function test_can_view_room_categories_page(): void
    {
        $response = $this->get(route('property-manager.room-categories.index'));

        $response->assertStatus(200);
        $response->assertSeeLivewire('property-manager.room-categories.index');
    }

    public function test_can_list_room_categories(): void
    {
        $categories = RoomCategory::factory()->count(3)->create();

        Livewire::test('property-manager.room-categories.index')
            ->assertSee($categories[0]->name)
            ->assertSee($categories[1]->name)
            ->assertSee($categories[2]->name);
    }

    public function test_can_create_room_category(): void
    {
        Livewire::test('property-manager.room-categories.index')
            ->callTableAction('create', data: [
                'code' => 'TEST',
                'name' => 'Test Category',
                'description' => 'Test description',
            ]);

        $this->assertDatabaseHas('room_categories', [
            'code' => 'TEST',
            'name' => 'Test Category',
        ]);
    }

    public function test_can_edit_room_category(): void
    {
        $category = RoomCategory::factory()->create();

        Livewire::test('property-manager.room-categories.index')
            ->callTableAction('edit', $category, data: [
                'name' => 'Updated Name',
            ]);

        $this->assertDatabaseHas('room_categories', [
            'id' => $category->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_can_delete_room_category(): void
    {
        $category = RoomCategory::factory()->create();

        Livewire::test('property-manager.room-categories.index')
            ->callTableAction('delete', $category);

        $this->assertDatabaseMissing('room_categories', [
            'id' => $category->id,
        ]);
    }
}
