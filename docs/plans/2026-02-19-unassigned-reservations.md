# Unassigned Reservations Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Display bookings without a room below the booking calendar and allow assigning a room via SortableJS drag-and-drop or a quick-assign dropdown.

**Architecture:** Add `$unassignedBookings` to the `Dashboard` Livewire component, populated in `loadData()`. Add an `assignRoom()` method. Render a panel below the calendar in the blade view with draggable cards. SortableJS (npm) is initialised via Alpine.js `x-init` on both the card list and each room row — on drop, call `$wire.assignRoom()`.

**Tech Stack:** Laravel 12, Livewire 4, Alpine.js (bundled with Flux), SortableJS (npm), Tailwind CSS v4, Vite 7, PHPUnit 11.

---

### Task 1: Install SortableJS

**Files:**
- Modify: `package.json`
- Modify: `resources/js/app.js`

**Step 1: Install SortableJS via npm**

```bash
npm install sortablejs
```

Expected: `sortablejs` appears in `node_modules/` and `package.json` dependencies.

**Step 2: Import SortableJS globally in `app.js`**

`resources/js/app.js` is currently empty. Add:

```js
import Sortable from 'sortablejs';
window.Sortable = Sortable;
```

This makes `Sortable` available to Alpine.js `x-init` expressions in blade templates.

**Step 3: Build assets**

```bash
npm run build
```

Expected: No errors. `public/build/` updated.

**Step 4: Commit**

```bash
git add package.json package-lock.json resources/js/app.js
git commit -m "feat: install and expose SortableJS globally"
```

---

### Task 2: Add `$unassignedBookings` property and `assignRoom()` method

**Files:**
- Modify: `app/Livewire/PropertyManager/Bookings/Dashboard.php`

**Step 1: Write failing tests first**

Open `tests/Feature/PropertyManager/BookingDashboardTest.php` and add three new tests after the existing ones:

```php
public function test_unassigned_bookings_are_loaded_into_component(): void
{
    $status = BookingStatus::factory()->create(['name' => 'Pending', 'color' => 'gray']);
    $creator = User::factory()->create();

    // Booking without a room
    $unassigned = Booking::factory()->create([
        'room_id' => null,
        'booking_status_id' => $status->id,
        'created_by' => $creator->id,
        'check_in_date' => Carbon::today()->addDays(1),
        'check_out_date' => Carbon::today()->addDays(3),
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
        'check_in_date' => Carbon::today()->addDays(1),
        'check_out_date' => Carbon::today()->addDays(3),
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
        'check_in_date' => Carbon::today()->addDays(1),
        'check_out_date' => Carbon::today()->addDays(3),
    ]);

    $component = Livewire::test(Dashboard::class);
    $component->call('assignRoom', $booking->id, $room->id);

    $this->assertFalse(
        $component->instance()->unassignedBookings->contains('id', $booking->id)
    );
}
```

**Step 2: Run tests to confirm they fail**

```bash
php artisan test --compact --filter="test_unassigned_bookings_are_loaded|test_assign_room_sets_room|test_booking_leaves_unassigned" tests/Feature/PropertyManager/BookingDashboardTest.php
```

Expected: 3 failures — `unassignedBookings` property does not exist.

**Step 3: Add the property and method to `Dashboard.php`**

Add the public property after the existing `$stats` property (around line 36):

```php
public $unassignedBookings;
```

At the end of `loadData()`, after `$this->calculateStats()` (around line 66), add:

```php
$this->unassignedBookings = Booking::with(['guest', 'bookingStatus'])
    ->whereNull('room_id')
    ->get();
```

After the closing brace of `updatedEndDate()`, add the new method:

```php
public function assignRoom(int $bookingId, int $roomId): void
{
    Booking::findOrFail($bookingId)->update(['room_id' => $roomId]);
    $this->loadData();
}
```

**Step 4: Run tests to confirm they pass**

```bash
php artisan test --compact --filter="test_unassigned_bookings_are_loaded|test_assign_room_sets_room|test_booking_leaves_unassigned" tests/Feature/PropertyManager/BookingDashboardTest.php
```

Expected: 3 passes.

**Step 5: Run full test suite**

```bash
php artisan test --compact
```

Expected: All tests pass.

**Step 6: Run Pint**

```bash
vendor/bin/pint --dirty --format agent
```

**Step 7: Commit**

```bash
git add app/Livewire/PropertyManager/Bookings/Dashboard.php tests/Feature/PropertyManager/BookingDashboardTest.php
git commit -m "feat: add unassigned bookings property and assignRoom method"
```

---

### Task 3: Add `data-room-id` attributes to calendar room rows

**Files:**
- Modify: `resources/views/livewire/property-manager/bookings/dashboard.blade.php`

The SortableJS drop targets need to know which room they represent. Find the room row `<div>` that wraps the calendar columns (around line 129):

```html
@foreach($rooms as $room)
    <div class="flex border-b border-neutral-200 ...">
```

Change it to:

```html
@foreach($rooms as $room)
    <div class="flex border-b border-neutral-200 transition-colors hover:bg-neutral-50 dark:border-neutral-700 dark:hover:bg-neutral-800/30"
         data-room-id="{{ $room->id }}">
```

No tests needed for a data attribute — it will be verified visually and exercised by the SortableJS wiring in Task 5.

**Commit:**

```bash
git add resources/views/livewire/property-manager/bookings/dashboard.blade.php
git commit -m "feat: add data-room-id attributes to calendar room rows"
```

---

### Task 4: Add the Unassigned Reservations panel to the blade view

**Files:**
- Modify: `resources/views/livewire/property-manager/bookings/dashboard.blade.php`

Add the following block **between** the closing `</div>` of the booking calendar section and the pending bookings warning (before `@if($stats['pending_bookings'] > 0)`):

```html
@if($unassignedBookings->isNotEmpty())
    <div class="rounded-lg border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-900">
        <h2 class="mb-4 text-xl font-semibold text-neutral-900 dark:text-neutral-100">
            Unassigned Reservations
            <span class="ml-2 rounded-full bg-amber-100 px-2.5 py-0.5 text-sm font-medium text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                {{ $unassignedBookings->count() }}
            </span>
        </h2>

        <div
            id="unassigned-reservations-list"
            class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3"
            x-data
            x-init="
                new Sortable($el, {
                    group: { name: 'reservations', pull: 'clone', put: false },
                    sort: false,
                    animation: 150,
                    ghostClass: 'opacity-40',
                });
            "
        >
            @foreach($unassignedBookings as $booking)
                <div
                    class="cursor-grab rounded-lg border border-neutral-200 bg-neutral-50 p-4 shadow-sm transition-shadow hover:shadow-md dark:border-neutral-700 dark:bg-neutral-800"
                    data-booking-id="{{ $booking->id }}"
                >
                    <div class="mb-2 flex items-center justify-between gap-2">
                        <span class="font-semibold text-neutral-900 dark:text-neutral-100">
                            {{ $booking->guest->full_name }}
                        </span>
                        <span class="shrink-0 rounded-full px-2 py-0.5 text-xs font-medium text-white"
                              style="background-color: {{ $booking->bookingStatus->color === 'blue' ? '#3b82f6' : ($booking->bookingStatus->color === 'green' ? '#10b981' : ($booking->bookingStatus->color === 'yellow' ? '#f59e0b' : '#6b7280')) }};">
                            {{ $booking->bookingStatus->name }}
                        </span>
                    </div>

                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                        {{ $booking->check_in_date->format('M d') }} &rarr; {{ $booking->check_out_date->format('M d, Y') }}
                        <span class="ml-1 text-xs">({{ $booking->number_of_nights }} night{{ $booking->number_of_nights !== 1 ? 's' : '' }})</span>
                    </p>

                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                        {{ $booking->number_of_adults }} adult{{ $booking->number_of_adults !== 1 ? 's' : '' }}
                        @if($booking->number_of_children > 0)
                            &bull; {{ $booking->number_of_children }} child{{ $booking->number_of_children !== 1 ? 'ren' : '' }}
                        @endif
                    </p>

                    <div class="mt-3">
                        <select
                            class="w-full rounded-md border border-neutral-300 bg-white px-3 py-1.5 text-sm text-neutral-700 shadow-sm focus:border-blue-500 focus:outline-none dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200"
                            wire:change="assignRoom({{ $booking->id }}, $event.target.value)"
                        >
                            <option value="">Quick assign a room…</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->room_number }} &mdash; {{ $room->roomCategory->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
```

**Commit:**

```bash
git add resources/views/livewire/property-manager/bookings/dashboard.blade.php
git commit -m "feat: add unassigned reservations panel with quick-assign dropdown"
```

---

### Task 5: Wire SortableJS drop targets to `assignRoom` on calendar room rows

**Files:**
- Modify: `resources/views/livewire/property-manager/bookings/dashboard.blade.php`

Each room row needs to be a SortableJS drop target. Find the room row `<div>` (modified in Task 3) and add Alpine.js attributes:

```html
@foreach($rooms as $room)
    <div class="flex border-b border-neutral-200 transition-colors hover:bg-neutral-50 dark:border-neutral-700 dark:hover:bg-neutral-800/30"
         data-room-id="{{ $room->id }}"
         x-data
         x-init="
             new Sortable($el, {
                 group: { name: 'reservations', pull: false, put: true },
                 animation: 150,
                 ghostClass: 'bg-blue-50',
                 onAdd(event) {
                     const bookingId = event.item.dataset.bookingId;
                     const roomId = $el.dataset.roomId;
                     $wire.assignRoom(parseInt(bookingId), parseInt(roomId));
                     event.item.remove();
                 },
             });
         ">
```

This wires the drop: when a card is dropped onto a room row, `onAdd` fires, reads the booking ID from the card and the room ID from the row, calls `$wire.assignRoom()`, and removes the dragged clone from the DOM (Livewire re-renders clean state).

**Step: Build assets after all JS changes**

```bash
npm run build
```

Expected: No errors.

**Step: Run full test suite**

```bash
php artisan test --compact
```

Expected: All tests pass.

**Step: Run Pint**

```bash
vendor/bin/pint --dirty --format agent
```

**Commit:**

```bash
git add resources/views/livewire/property-manager/bookings/dashboard.blade.php
git commit -m "feat: wire SortableJS drag-and-drop onto calendar room rows"
```

---

### Task 6: Manual verification checklist

Before considering this feature done, verify in the browser:

1. Create a reservation with no room selected — confirm it appears in the "Unassigned Reservations" panel
2. Use the **quick-assign** dropdown on a card — confirm the card disappears from the panel and the booking appears in the calendar
3. **Drag** a card from the panel onto a room row — confirm the same outcome
4. When all reservations are assigned, confirm the panel is hidden entirely
5. Test dark mode appearance

---

### Notes

- The `$wire.assignRoom()` call in `onAdd` uses Livewire's magic `$wire` object, available because the blade template is rendered inside a Livewire component context.
- `event.item.remove()` is needed because SortableJS with `pull: 'clone'` leaves a copy in the drop target; we remove it since Livewire re-renders the correct state.
- The colour mapping inline in the blade (`$booking->bookingStatus->color === 'blue' ? ...`) mirrors the existing pattern already used in the calendar cells.
