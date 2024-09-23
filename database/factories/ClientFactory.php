<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'birth_date' => $this->faker->date('Y-m-d'),
            'address' => $this->faker->address,
            'neighborhood' => $this->faker->word,
            'postal_code' => $this->faker->postcode,
        ];
    }
}
