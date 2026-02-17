<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureUserIsPropertyManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_manager_can_access_property_manager_routes(): void
    {
        $user = User::factory()->create(['property_manager' => true]);

        $response = $this->actingAs($user)->get(route('property-manager.users.index'));

        $response->assertStatus(200);
    }

    public function test_non_property_manager_cannot_access_property_manager_routes(): void
    {
        $user = User::factory()->create(['property_manager' => false]);

        $response = $this->actingAs($user)->get(route('property-manager.users.index'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_property_manager_routes(): void
    {
        $response = $this->get(route('property-manager.users.index'));

        $response->assertStatus(403);
    }

    public function test_property_manager_can_access_all_room_management_routes(): void
    {
        $user = User::factory()->create(['property_manager' => true]);

        $routes = [
            'property-manager.rooms.index',
            'property-manager.room-categories.index',
            'property-manager.attributes.index',
            'property-manager.statuses.index',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get(route($route));
            $response->assertStatus(200);
        }
    }
}
