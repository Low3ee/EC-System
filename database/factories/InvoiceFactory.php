<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'room_id' => Room::factory(),
            'tenant_id' => Tenant::factory(),
            'total_amount' => $this->faker->randomFloat(2, 100, 1000),
            'due_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'status' => 'unpaid',
        ];
    }
}
