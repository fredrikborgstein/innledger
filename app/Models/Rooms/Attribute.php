<?php

namespace App\Models\Rooms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function roomCategories(): BelongsToMany
    {
        return $this->belongsToMany(RoomCategory::class, 'attribute_room_category')
            ->withTimestamps();
    }
}
