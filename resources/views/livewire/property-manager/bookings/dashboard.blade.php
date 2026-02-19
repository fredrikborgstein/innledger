<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Today's Check-ins</p>
                    <p class="mt-2 text-4xl font-bold">{{ $stats['today_checkins'] }}</p>
                </div>
                <div class="rounded-full bg-white/20 p-3 backdrop-blur-sm">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-gradient-to-br from-green-500 to-green-600 p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Today's Check-outs</p>
                    <p class="mt-2 text-4xl font-bold">{{ $stats['today_checkouts'] }}</p>
                </div>
                <div class="rounded-full bg-white/20 p-3 backdrop-blur-sm">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Occupied Rooms</p>
                    <p class="mt-2 text-4xl font-bold">{{ $stats['occupied_rooms'] }}<span class="text-2xl opacity-75">/{{ $stats['available_rooms'] + $stats['occupied_rooms'] }}</span></p>
                </div>
                <div class="rounded-full bg-white/20 p-3 backdrop-blur-sm">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-900">
        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-4">
                <h2 class="text-2xl font-semibold">Booking Calendar</h2>
                {{ $this->createBookingAction }}
            </div>

            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                <div class="flex gap-2">
                    <button wire:click="setViewMode('today')" class="rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ $viewMode === 'today' ? 'bg-blue-600 text-white shadow-md' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700' }}">
                        Today
                    </button>
                    <button wire:click="setViewMode('week')" class="rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ $viewMode === 'week' ? 'bg-blue-600 text-white shadow-md' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700' }}">
                        Week
                    </button>
                    <button wire:click="setViewMode('month')" class="rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ $viewMode === 'month' ? 'bg-blue-600 text-white shadow-md' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700' }}">
                        Month
                    </button>
                </div>

                @if($viewMode === 'today')
                    <div class="flex items-center gap-2">
                        <button wire:click="previousDay" class="rounded-lg p-2 hover:bg-neutral-100 dark:hover:bg-neutral-800">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <span class="min-w-[200px] text-center text-sm font-medium">
                            {{ \Carbon\Carbon::parse($startDate)->format('D, M d') }} &rarr; {{ \Carbon\Carbon::parse($endDate)->format('D, M d, Y') }}
                        </span>
                        <button wire:click="nextDay" class="rounded-lg p-2 hover:bg-neutral-100 dark:hover:bg-neutral-800">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <button wire:click="today" class="ml-2 rounded-lg bg-neutral-100 px-3 py-2 text-sm font-medium hover:bg-neutral-200 dark:bg-neutral-800 dark:hover:bg-neutral-700">
                            Today
                        </button>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-neutral-600 dark:text-neutral-400">From:</label>
                            <input type="date" wire:model.live="startDate" class="rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-600 dark:bg-neutral-800">
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-neutral-600 dark:text-neutral-400">To:</label>
                            <input type="date" wire:model.live="endDate" class="rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-600 dark:bg-neutral-800">
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @php
            $calendarDates = $this->getCalendarDates();
            $dateCount = count($calendarDates);
        @endphp

        <div class="overflow-x-auto">
            <div class="min-w-full">
                <div class="sticky top-0 z-10 flex border-b-2 border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <div class="w-48 shrink-0 px-4 py-3">
                        <span class="text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Room</span>
                    </div>
                    <div class="flex flex-1">
                        @foreach($calendarDates as $date)
                            <div class="flex-1 border-l border-neutral-200 px-2 py-3 text-center dark:border-neutral-700" style="min-width: 100px;">
                                <div class="text-xs font-semibold uppercase text-neutral-500 dark:text-neutral-400">
                                    {{ $date->format('D') }}
                                </div>
                                <div class="text-lg font-bold {{ $date->isToday() ? 'text-blue-600 dark:text-blue-400' : 'text-neutral-900 dark:text-neutral-100' }}">
                                    {{ $date->format('d') }}
                                </div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ $date->format('M') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @foreach($rooms as $room)
                    <div class="flex border-b border-neutral-200 transition-colors hover:bg-neutral-50 dark:border-neutral-700 dark:hover:bg-neutral-800/30"
                         data-room-id="{{ $room->id }}">
                        <div class="w-48 shrink-0 border-r border-neutral-200 px-4 py-4 dark:border-neutral-700">
                            <div class="flex flex-col gap-1">
                                <span class="text-lg font-bold text-neutral-900 dark:text-neutral-100">{{ $room->room_number }}</span>
                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ $room->roomCategory->name }}</span>
                            </div>
                        </div>

                        <div class="relative flex flex-1">
                            @php
                                $processedBookings = [];
                            @endphp

                            @foreach($calendarDates as $index => $date)
                                @php
                                    $booking = $this->getBookingForRoomAndDate($room->id, $date);
                                    $isFirstDay = $booking && !in_array($booking->id, $processedBookings);

                                    if ($isFirstDay) {
                                        $processedBookings[] = $booking->id;
                                        $lastVisibleDate = $calendarDates[count($calendarDates) - 1];
                                        $bookingStart = max($booking->check_in_date, $calendarDates[0]);
                                        $bookingEnd = min($booking->check_out_date, $lastVisibleDate);
                                        $duration = $bookingStart->diffInDays($bookingEnd) + 1;

                                        $checkInIsVisible = $booking->check_in_date >= $calendarDates[0];
                                        $checkOutIsVisible = $booking->check_out_date <= $lastVisibleDate;
                                        $leftPercent = $checkInIsVisible ? 50 : 0;
                                        $rightPercent = $checkOutIsVisible ? 50 : 100;
                                        $widthPercent = ($duration - 1) * 100 + ($rightPercent - $leftPercent);
                                    }
                                @endphp

                                <div class="relative flex-1 border-l border-neutral-200 dark:border-neutral-700" style="min-width: 100px;">
                                    @if($isFirstDay)
                                        <div class="absolute inset-y-1 z-10 flex items-center overflow-hidden rounded-lg px-3 py-2 shadow-sm"
                                             style="left: {{ $leftPercent }}%; width: calc({{ $widthPercent }}% + {{ $duration - 1 }}px);
                                                    background: linear-gradient(135deg, {{ $booking->bookingStatus->color === 'blue' ? '#3b82f6' : ($booking->bookingStatus->color === 'green' ? '#10b981' : ($booking->bookingStatus->color === 'yellow' ? '#f59e0b' : '#6b7280')) }} 0%, {{ $booking->bookingStatus->color === 'blue' ? '#2563eb' : ($booking->bookingStatus->color === 'green' ? '#059669' : ($booking->bookingStatus->color === 'yellow' ? '#d97706' : '#4b5563')) }} 100%);">
                                            <div class="flex min-w-0 items-center gap-2 text-white">
                                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span class="truncate text-sm font-medium">{{ $booking->guest->full_name }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

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

    <x-filament-actions::modals />
</div>
