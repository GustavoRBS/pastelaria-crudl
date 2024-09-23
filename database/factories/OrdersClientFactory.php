<?php

namespace Database\Factories;

use App\Models\OrdersClient;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrdersClientFactory extends Factory
{
    protected $model = OrdersClient::class;

    public function definition()
    {
        return [
            'created_at' => now(),
            'updated_at' => null,
            'deleted_at' => null,
            'created_by' => $this->faker->name,
            'updated_by' => null,
            'deleted_by' => null,
        ];
    }
}
