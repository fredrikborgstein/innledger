<?php

namespace App\Livewire\PropertyManager\Bookings;

use App\Models\Bookings\Booking;
use App\Models\Rooms\Room;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public $selectedDate;

    public $viewMode = 'month';

    public $rooms;

    public $bookings;

    public $stats;

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->loadData();
    }

    public function loadData()
    {
        $date = Carbon::parse($this->selectedDate);

        $this->rooms = Room::with(['roomCategory', 'status'])->get();

        if ($this->viewMode === 'month') {
            $startDate = $date->copy()->startOfMonth();
            $endDate = $date->copy()->endOfMonth();
        } else {
            $startDate = $date->copy()->startOfWeek();
            $endDate = $date->copy()->endOfWeek();
        }

        $this->bookings = Booking::with(['guest', 'room', 'bookingStatus'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in_date', [$startDate, $endDate])
                    ->orWhereBetween('check_out_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('check_in_date', '<=', $startDate)
                            ->where('check_out_date', '>=', $endDate);
                    });
            })
            ->get();

        $this->calculateStats();
    }

    public function calculateStats()
    {
        $today = now()->startOfDay();

        $this->stats = [
            'total_bookings' => Booking::count(),
            'today_checkins' => Booking::whereDate('check_in_date', $today)->count(),
            'today_checkouts' => Booking::whereDate('check_out_date', $today)->count(),
            'occupied_rooms' => Booking::where('check_in_date', '<=', $today)
                ->where('check_out_date', '>=', $today)
                ->whereHas('bookingStatus', fn ($q) => $q->where('name', 'Checked In'))
                ->count(),
            'available_rooms' => Room::whereHas('status', fn ($q) => $q->where('name', 'Available'))->count(),
            'pending_bookings' => Booking::whereHas('bookingStatus', fn ($q) => $q->where('name', 'Pending'))->count(),
        ];
    }

    public function previousPeriod()
    {
        $date = Carbon::parse($this->selectedDate);
        $this->selectedDate = $this->viewMode === 'month'
            ? $date->subMonth()->format('Y-m-d')
            : $date->subWeek()->format('Y-m-d');
        $this->loadData();
    }

    public function nextPeriod()
    {
        $date = Carbon::parse($this->selectedDate);
        $this->selectedDate = $this->viewMode === 'month'
            ? $date->addMonth()->format('Y-m-d')
            : $date->addWeek()->format('Y-m-d');
        $this->loadData();
    }

    public function today()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->loadData();
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.property-manager.bookings.dashboard');
    }
}
