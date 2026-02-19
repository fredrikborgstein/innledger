# Unassigned Reservations — Design

**Date:** 2026-02-19
**Status:** Approved

## Problem

Bookings created without a room are invisible on the calendar grid (which is room-based). Staff need to see and act on them.

## Solution

Add an **Unassigned Reservations panel** below the booking calendar on `Dashboard.php`. Reservations without a room appear as draggable cards. Staff can assign a room by dragging a card onto a room row, or via a quick-assign dropdown.

## Components

### Unassigned Reservations Panel

- Rendered below the calendar only when at least one booking has `room_id = null`
- Hidden entirely when all bookings are assigned
- Each card displays: guest name, check-in → check-out dates, number of nights, adults/children count, status badge (coloured)

### Quick Assign

- Each card has a room select dropdown
- Selecting a room immediately calls `assignRoom($bookingId, $roomId)` on the Livewire component

### Drag-and-Drop (SortableJS)

- SortableJS installed via npm
- Alpine.js `x-init` initialises SortableJS on the unassigned list and on each room row
- Both share the same SortableJS group name (`reservations`)
- On drop, Alpine calls `$wire.assignRoom(bookingId, roomId)` using `data-booking-id` and `data-room-id` attributes
- No page reload — Livewire re-renders reactively

## Server Side

New method on `Dashboard.php`:

```php
public function assignRoom(int $bookingId, int $roomId): void
{
    Booking::findOrFail($bookingId)->update(['room_id' => $roomId]);
    $this->loadData();
}
```

New public property `$unassignedBookings` populated in `loadData()`:

```php
$this->unassignedBookings = Booking::with(['guest', 'bookingStatus'])
    ->whereNull('room_id')
    ->get();
```

## Testing

- Unassigned bookings appear in `$unassignedBookings` collection
- `assignRoom()` sets `room_id` correctly
- Booking no longer appears in `$unassignedBookings` after assignment
- Panel is hidden when no unassigned bookings exist
