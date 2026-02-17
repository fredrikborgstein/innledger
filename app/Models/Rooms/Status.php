<?php

namespace App\Models\Rooms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    protected $fillable = [
        'name',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
