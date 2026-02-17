<?php

namespace Database\Seeders;

use App\Models\Rooms\Attribute;
use App\Models\Rooms\Room;
use App\Models\Rooms\RoomCategory;
use App\Models\Rooms\Status;
use Illuminate\Database\Seeder;

class RoomManagementSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Available'],
            ['name' => 'Occupied'],
            ['name' => 'Maintenance'],
            ['name' => 'Out of Service'],
            ['name' => 'Reserved'],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }

        $attributes = [
            ['code' => 'wifi', 'name' => 'WiFi', 'description' => 'High-speed wireless internet'],
            ['code' => 'tv', 'name' => 'TV', 'description' => 'Flat screen television'],
            ['code' => 'minibar', 'name' => 'Minibar', 'description' => 'Stocked minibar'],
            ['code' => 'safe', 'name' => 'Safe', 'description' => 'In-room safe'],
            ['code' => 'balcony', 'name' => 'Balcony', 'description' => 'Private balcony'],
            ['code' => 'ac', 'name' => 'Air Conditioning', 'description' => 'Climate control'],
            ['code' => 'bathtub', 'name' => 'Bathtub', 'description' => 'Full bathtub'],
            ['code' => 'shower', 'name' => 'Shower', 'description' => 'Walk-in shower'],
            ['code' => 'kitchenette', 'name' => 'Kitchenette', 'description' => 'Small kitchen area'],
            ['code' => 'workspace', 'name' => 'Workspace', 'description' => 'Desk and chair'],
        ];

        foreach ($attributes as $attribute) {
            Attribute::create($attribute);
        }

        $roomCategories = [
            [
                'code' => 'STD',
                'name' => 'Standard Room',
                'description' => 'Basic room with essential amenities',
                'attributes' => ['wifi', 'tv', 'ac', 'shower'],
            ],
            [
                'code' => 'DLX',
                'name' => 'Deluxe Room',
                'description' => 'Upgraded room with additional comfort',
                'attributes' => ['wifi', 'tv', 'minibar', 'safe', 'ac', 'bathtub', 'workspace'],
            ],
            [
                'code' => 'STE',
                'name' => 'Suite',
                'description' => 'Spacious suite with separate living area',
                'attributes' => ['wifi', 'tv', 'minibar', 'safe', 'balcony', 'ac', 'bathtub', 'kitchenette', 'workspace'],
            ],
            [
                'code' => 'PSTE',
                'name' => 'Premium Suite',
                'description' => 'Luxury suite with premium amenities',
                'attributes' => ['wifi', 'tv', 'minibar', 'safe', 'balcony', 'ac', 'bathtub', 'shower', 'kitchenette', 'workspace'],
            ],
        ];

        foreach ($roomCategories as $categoryData) {
            $attributeCodes = $categoryData['attributes'];
            unset($categoryData['attributes']);

            $category = RoomCategory::create($categoryData);

            $attributeIds = Attribute::whereIn('code', $attributeCodes)->pluck('id');
            $category->attributes()->attach($attributeIds);
        }

        $availableStatus = Status::where('name', 'Available')->first();
        $standardCategory = RoomCategory::where('code', 'STD')->first();
        $deluxeCategory = RoomCategory::where('code', 'DLX')->first();
        $suiteCategory = RoomCategory::where('code', 'STE')->first();

        $rooms = [
            ['room_number' => '101', 'room_category_id' => $standardCategory->id, 'status_id' => $availableStatus->id],
            ['room_number' => '102', 'room_category_id' => $standardCategory->id, 'status_id' => $availableStatus->id],
            ['room_number' => '103', 'room_category_id' => $standardCategory->id, 'status_id' => $availableStatus->id],
            ['room_number' => '201', 'room_category_id' => $deluxeCategory->id, 'status_id' => $availableStatus->id],
            ['room_number' => '202', 'room_category_id' => $deluxeCategory->id, 'status_id' => $availableStatus->id],
            ['room_number' => '301', 'room_category_id' => $suiteCategory->id, 'status_id' => $availableStatus->id],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
