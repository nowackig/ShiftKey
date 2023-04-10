<?php

declare(strict_types=1);

namespace Database\Factories;

use App\ORM\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\ORM\Trip>
 */
class TripFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Trip::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid,
            'user_id' => $this->faker->randomNumber(),
            'car_uuid' => $this->faker->uuid,
            'date' => $this->faker->date(),
            'miles' => $this->faker->randomFloat(2),
        ];
    }
}
