<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'category' => $this->faker->randomElement(['park', 'river', 'forest']),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'is_featured' => $this->faker->boolean,
            'team_id' => Team::factory(),
        ];
    }
}
