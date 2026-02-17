<?php

namespace Database\Factories\Rooms;

use App\Models\Rooms\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->slug(1),
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
        ];
    }
}
