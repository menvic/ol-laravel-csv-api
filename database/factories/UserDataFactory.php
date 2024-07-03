<?php

namespace Database\Factories;

use App\CarModelEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserData>
 */
class UserDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(50),
            'last_name' => $this->faker->lastName(50),
            'age' => $this->faker->numberBetween(18, 80),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'mobile_number' => $this->faker->unique()->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'city' => $this->faker->city,
            'login' => $this->faker->userName,
            'car_model' => $this->faker->randomElement(collect(CarModelEnum::cases())->pluck('name')),
            'salary' => $this->faker->numberBetween(1000, 5000)
        ];
    }
}
