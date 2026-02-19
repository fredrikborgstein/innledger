<?php

namespace App\Models\Bookings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookingStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function colorHex(): string
    {
        return match ($this->color) {
            'blue' => '#3b82f6',
            'green' => '#10b981',
            'yellow' => '#f59e0b',
            default => '#6b7280',
        };
    }

    public function colorHexDark(): string
    {
        return match ($this->color) {
            'blue' => '#2563eb',
            'green' => '#059669',
            'yellow' => '#d97706',
            default => '#4b5563',
        };
    }
}
