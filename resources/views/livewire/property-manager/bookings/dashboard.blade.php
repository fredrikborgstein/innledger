<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-lg border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Today's Check-ins</p>
                    <p class="mt-2 text-3xl font-bold">{{ $stats['today_checkins'] }}</p>
                </div>
                <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/20">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Today's Check-outs</p>
                    <p class="mt-2 text-3xl font-bold">{{ $stats['today_checkouts'] }}</p>
                </div>
                <div class="rounded-full bg-green-100 p-3 dark:bg-green-900/20">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Occupied Rooms</p>
                    <p class="mt-2 text-3xl font-bold">{{ $stats['occupied_rooms'] }} / {{ $stats['available_rooms'] + $stats['occupied_rooms'] }}</p>
                </div>
                <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900/20">
                    <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-900">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-semibold">Booking Calendar</h2>
            <div class="flex items-center gap-4">
                <div class="flex gap-2">
                    <button wire:click="setViewMode('week')" class="rounded px-3 py-1 text-sm {{ $viewMode === 'week' ? 'bg-blue-600 text-white' : 'bg-neutral-100 dark:bg-neutral-800' }}">
                        Week
                    </button>
                    <button wire:click="setViewMode('month')" class="rounded px-3 py-1 text-sm {{ $viewMode === 'month' ? 'bg-blue-600 text-white' : 'bg-neutral-100 dark:bg-neutral-800' }}">
                        Month
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <button wire:click="previousPeriod" class="rounded p-2 hover:bg-neutral-100 dark:hover:bg-neutral-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button wire:click="today" class="rounded px-3 py-1 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">
                        Today
                    </button>
                    <button wire:click="nextPeriod" class="rounded p-2 hover:bg-neutral-100 dark:hover:bg-neutral-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
                <span class="text-lg font-medium">{{ \Carbon\Carbon::parse($selectedDate)->format('F Y') }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-200 dark:border-neutral-700">
                        <th class="px-4 py-2 text-left text-sm font-medium">Room</th>
                        <th class="px-4 py-2 text-left text-sm font-medium">Category</th>
                        <th class="px-4 py-2 text-left text-sm font-medium">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-medium">Current Booking</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                        @php
                            $currentBooking = $bookings->first(function($booking) use ($room) {
                                return $booking->room_id === $room->id
                                    && $booking->check_in_date <= now()
                                    && $booking->check_out_date >= now();
                            });
                        @endphp
                        <tr class="border-b border-neutral-200 dark:border-neutral-700">
                            <td class="px-4 py-3 font-medium">{{ $room->room_number }}</td>
                            <td class="px-4 py-3 text-sm">{{ $room->roomCategory->name }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold
                                    {{ $room->status->name === 'Available' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400' }}">
                                    {{ $room->status->name }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($currentBooking)
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium">{{ $currentBooking->guest->full_name }}</span>
                                        <span class="text-neutral-500">•</span>
                                        <span class="text-neutral-600 dark:text-neutral-400">
                                            {{ $currentBooking->check_in_date->format('M d') }} - {{ $currentBooking->check_out_date->format('M d') }}
                                        </span>
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                            {{ $currentBooking->bookingStatus->name }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-neutral-400">No active booking</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($stats['pending_bookings'] > 0)
        <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 dark:border-yellow-800 dark:bg-yellow-900/20">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span class="font-medium text-yellow-800 dark:text-yellow-200">
                    You have {{ $stats['pending_bookings'] }} pending booking(s) that need confirmation
                </span>
            </div>
        </div>
    @endif
</div>
