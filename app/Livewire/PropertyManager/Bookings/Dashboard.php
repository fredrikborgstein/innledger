<?php

namespace App\Livewire\PropertyManager\Bookings;

use App\Models\Bookings\Booking;
use App\Models\Bookings\BookingStatus;
use App\Models\Bookings\Guest;
use App\Models\Rooms\Room;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Section as SchemaSection;
use Livewire\Component;

class Dashboard extends Component implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    public $selectedDate;

    public $startDate;

    public $endDate;

    public $viewMode = 'today';

    public $rooms;

    public $bookings;

    public $stats;

    public $unassignedBookings;

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->addDay()->format('Y-m-d');
        $this->loadData();
    }

    public function loadData()
    {
        $this->rooms = Room::with(['roomCategory', 'status'])->get();

        $startDate = Carbon::parse($this->startDate)->startOfDay();
        $endDate = Carbon::parse($this->endDate)->endOfDay();

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

        $this->unassignedBookings = Booking::with(['guest', 'bookingStatus'])
            ->whereNull('room_id')
            ->whereBetween('check_in_date', [$this->startDate, $this->endDate])
            ->get();
    }

    public function getCalendarDates()
    {
        $dates = [];
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        $current = $start->copy();
        while ($current <= $end) {
            $dates[] = $current->copy();
            $current->addDay();
        }

        return $dates;
    }

    public function getBookingForRoomAndDate($roomId, $date)
    {
        return $this->bookings->first(function ($booking) use ($roomId, $date) {
            return $booking->room_id === $roomId
                && $booking->check_in_date <= $date
                && $booking->check_out_date > $date;
        });
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

    public function previousDay()
    {
        $date = Carbon::parse($this->startDate)->subDay();
        $this->startDate = $date->format('Y-m-d');
        $this->endDate = $date->copy()->addDay()->format('Y-m-d');
        $this->selectedDate = $date->format('Y-m-d');
        $this->loadData();
    }

    public function nextDay()
    {
        $date = Carbon::parse($this->startDate)->addDay();
        $this->startDate = $date->format('Y-m-d');
        $this->endDate = $date->copy()->addDay()->format('Y-m-d');
        $this->selectedDate = $date->format('Y-m-d');
        $this->loadData();
    }

    public function today()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->addDay()->format('Y-m-d');
        $this->viewMode = 'today';
        $this->loadData();
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;

        if ($mode === 'today') {
            $this->today();
        } elseif ($mode === 'week') {
            $date = Carbon::parse($this->selectedDate);
            $this->startDate = $date->copy()->startOfWeek()->format('Y-m-d');
            $this->endDate = $date->copy()->endOfWeek()->format('Y-m-d');
            $this->loadData();
        } elseif ($mode === 'month') {
            $date = Carbon::parse($this->selectedDate);
            $this->startDate = $date->copy()->startOfMonth()->format('Y-m-d');
            $this->endDate = $date->copy()->endOfMonth()->format('Y-m-d');
            $this->loadData();
        }
    }

    public function updatedStartDate()
    {
        $this->viewMode = 'custom';
        $this->loadData();
    }

    public function updatedEndDate()
    {
        $this->viewMode = 'custom';
        $this->loadData();
    }

    public function assignRoom(int $bookingId, int $roomId): void
    {
        $room = Room::findOrFail($roomId);
        Booking::findOrFail($bookingId)->update(['room_id' => $room->id]);
        $this->loadData();
    }

    public function createBookingAction(): Action
    {
        return Action::make('createBooking')
            ->label('Create Reservation')
            ->icon('heroicon-o-plus-circle')
            ->modalHeading('Create New Reservation')
            ->modalWidth('4xl')
            ->form([
                SchemaSection::make()
                    ->columns(2)
                    ->schema([
                        Select::make('guest_id')
                            ->label('Guest')
                            ->options(Guest::all()->pluck('full_name', 'id'))
                            ->searchable()
                            ->required()
                            ->columnSpan(2)
                            ->createOptionForm([
                                TextInput::make('first_name')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1),
                                TextInput::make('last_name')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1),
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1),
                                TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255)
                                    ->columnSpan(1),
                                TextInput::make('country')
                                    ->maxLength(255)
                                    ->columnSpan(2),
                                Textarea::make('address')
                                    ->rows(2)
                                    ->columnSpan(2),
                            ])
                            ->createOptionUsing(function (array $data) {
                                return Guest::create($data)->id;
                            }),
                        Select::make('room_id')
                            ->label('Room')
                            ->options(Room::with('roomCategory')->get()->mapWithKeys(function ($room) {
                                return [$room->id => $room->room_number.' - '.$room->roomCategory->name];
                            }))
                            ->searchable()
                            ->columnSpan(1),
                        Select::make('booking_status_id')
                            ->label('Status')
                            ->options(fn () => BookingStatus::pluck('name', 'id')->toArray())
                            ->default(fn () => BookingStatus::where('name', 'Pending')->first()?->id)
                            ->required()
                            ->columnSpan(1),
                        DatePicker::make('check_in_date')
                            ->label('Check-in Date')
                            ->required()
                            ->native(false)
                            ->minDate(today())
                            ->columnSpan(1),
                        DatePicker::make('check_out_date')
                            ->label('Check-out Date')
                            ->required()
                            ->native(false)
                            ->after('check_in_date')
                            ->columnSpan(1),
                        TextInput::make('number_of_adults')
                            ->label('Adults')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required()
                            ->columnSpan(1),
                        TextInput::make('number_of_children')
                            ->label('Children')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->columnSpan(1),
                        TextInput::make('total_price')
                            ->label('Total Price')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->columnSpan(1),
                        Textarea::make('special_requests')
                            ->label('Special Requests')
                            ->rows(2)
                            ->columnSpan(1),
                        Textarea::make('notes')
                            ->label('Internal Notes')
                            ->rows(2)
                            ->columnSpan(1),
                    ]),
            ])
            ->action(function (array $data) {
                $data['booking_number'] = Booking::generateBookingNumber();
                $data['created_by'] = auth()->id();

                Booking::create($data);

                $this->loadData();
            });
    }

    public function render()
    {
        return view('livewire.property-manager.bookings.dashboard');
    }
}
