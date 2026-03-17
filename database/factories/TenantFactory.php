<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'room_id' => Room::factory(),
            'base_rent' => $this->faker->numberBetween(1000, 5000),
            'due_day' => 1,
            'lease_start' => now(),
            'is_active' => true,
        ];
    }
}
