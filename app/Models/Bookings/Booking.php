<?php

namespace App\Models\Bookings;

use App\Models\Rooms\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'guest_id',
        'room_id',
        'booking_status_id',
        'created_by',
        'check_in_date',
        'check_out_date',
        'number_of_adults',
        'number_of_children',
        'total_price',
        'special_requests',
        'notes',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function bookingStatus(): BelongsTo
    {
        return $this->belongsTo(BookingStatus::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getNumberOfNightsAttribute(): int
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    public function getTotalPaidAttribute(): float
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }

    public function getBalanceAttribute(): float
    {
        return $this->total_price - $this->total_paid;
    }

    public static function generateBookingNumber(): string
    {
        do {
            $number = 'BK'.date('Ymd').strtoupper(substr(uniqid(), -6));
        } while (self::where('booking_number', $number)->exists());

        return $number;
    }
}
