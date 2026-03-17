<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'room_number' => $this->faker->unique()->word,
            'type' => 'standard',
            'default_rent' => $this->faker->numberBetween(1000, 5000),
            'is_available' => true,
        ];
    }
}
