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
}
