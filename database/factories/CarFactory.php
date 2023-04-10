<?php

declare(strict_types=1);

namespace Database\Factories;

use App\ORM\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\ORM\Car>
 */
class CarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Car::class;

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
            'make' => $this->faker->word(),
            'model' => $this->faker->words(3, true),
            'year' => $this->faker->randomNumber(4),
            'total' => $this->faker->randomNumber(),
        ];
    }
}
