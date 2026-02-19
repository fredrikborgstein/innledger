<?php

namespace Tests\Feature\PropertyManager;

use App\Livewire\PropertyManager\Bookings\Dashboard;
use App\Models\Bookings\Booking;
use App\Models\Bookings\BookingStatus;
use App\Models\Bookings\Guest;
use App\Models\Rooms\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BookingDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(['property_manager' => true]));
    }

    public function test_can_view_booking_dashboard(): void
    {
        $response = $this->get(route('bookings.dashboard'));

        $response->assertStatus(200);
        $response->assertSeeLivewire(Dashboard::class);
    }

    public function test_room_is_shown_as_occupied_during_stay(): void
    {
        $status = BookingStatus::factory()->create(['name' => 'Confirmed', 'color' => 'blue']);
        $room = Room::factory()->create();
        $creator = User::factory()->create();

        Booking::factory()->create([
            'room_id' => $room->id,
            'booking_status_id' => $status->id,
            'created_by' => $creator->id,
            'check_in_date' => Carbon::today()->subDays(2),
            'check_out_date' => Carbon::today()->addDays(2),
        ]);

        $component = Livewire::test(Dashboard::class);
        $component->call('loadData');

        $booking = $component->instance()->getBookingForRoomAndDate($room->id, Carbon::today());

        $this->assertNotNull($booking);
    }

    public function test_room_is_available_on_checkout_date(): void
    {
        $status = BookingStatus::factory()->create(['name' => 'Confirmed', 'color' => 'blue']);
        $room = Room::factory()->create();
        $creator = User::factory()->create();

        Booking::factory()->create([
            'room_id' => $room->id,
            'booking_status_id' => $status->id,
            'created_by' => $creator->id,
            'check_in_date' => Carbon::today()->subDays(2),
            'check_out_date' => Carbon::today(),
        ]);

        $component = Livewire::test(Dashboard::class);
        $component->call('loadData');

        $booking = $component->instance()->getBookingForRoomAndDate($room->id, Carbon::today());

        $this->assertNull($booking, 'Room should not appear occupied on its checkout date.');
    }

    public function test_room_can_accept_same_day_checkin_after_checkout(): void
    {
        $status = BookingStatus::factory()->create(['name' => 'Confirmed', 'color' => 'blue']);
        $room = Room::factory()->create();
        $creator = User::factory()->create();

        // Guest A checks out today
        Booking::factory()->create([
            'room_id' => $room->id,
            'booking_status_id' => $status->id,
            'created_by' => $creator->id,
            'check_in_date' => Carbon::today()->subDays(2),
            'check_out_date' => Carbon::today(),
        ]);

        // Guest B checks in today
        $newBooking = Booking::factory()->create([
            'room_id' => $room->id,
            'booking_status_id' => $status->id,
            'created_by' => $creator->id,
            'check_in_date' => Carbon::today(),
            'check_out_date' => Carbon::today()->addDays(3),
        ]);

        $component = Livewire::test(Dashboard::class);
        $component->call('loadData');

        $booking = $component->instance()->getBookingForRoomAndDate($room->id, Carbon::today());

        $this->assertNotNull($booking);
        $this->assertEquals($newBooking->id, $booking->id, 'The new check-in booking should be shown, not the departed guest.');
    }

    public function test_unassigned_bookings_are_loaded_into_component(): void
    {
        $status = BookingStatus::factory()->create(['name' => 'Pending', 'color' => 'gray']);
        $creator = User::factory()->create();

        $unassigned = Booking::factory()->create([
            'room_id' => null,
            'booking_status_id' => $status->id,
            'created_by' => $creator->id,
            'check_in_date' => Carbon::today(),
            'check_out_date' => Carbon::today()->addDays(2),
        ]);

        $component = Livewire::test(Dashboard::class);

        $this->assertTrue(
            $component->instance()->unassignedBookings->contains('id', $unassigned->id)
        );
    }

    public function test_assign_room_sets_room_id_on_booking(): void
    {
        $status = BookingStatus::factory()->create(['name' => 'Pending', 'color' => 'gray']);
        $room = Room::factory()->create();
        $creator = User::factory()->create();

        $booking = Booking::factory()->create([
            'room_id' => null,
            'booking_status_id' => $status->id,
            'created_by' => $creator->id,
            'check_in_date' => Carbon::today(),
            'check_out_date' => Carbon::today()->addDays(2),
        ]);

        Livewire::test(Dashboard::class)
            ->call('assignRoom', $booking->id, $room->id);

        $this->assertEquals($room->id, $booking->fresh()->room_id);
    }

    public function test_booking_leaves_unassigned_list_after_room_assigned(): void
    {
        $status = BookingStatus::factory()->create(['name' => 'Pending', 'color' => 'gray']);
        $room = Room::factory()->create();
        $creator = User::factory()->create();

        $booking = Booking::factory()->create([
            'room_id' => null,
            'booking_status_id' => $status->id,
            'created_by' => $creator->id,
            'check_in_date' => Carbon::today(),
            'check_out_date' => Carbon::today()->addDays(2),
        ]);

        $component = Livewire::test(Dashboard::class);
        $component->call('assignRoom', $booking->id, $room->id);

        $this->assertFalse(
            $component->instance()->unassignedBookings->contains('id', $booking->id)
        );
    }
}
