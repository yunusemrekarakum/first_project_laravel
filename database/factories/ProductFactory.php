<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->sentence(5),
            'category_id' => rand(1, 50),
            'image_path' => $this->faker->imageUrl(),
            'price' => $this->faker->randomFloat(2, 10),
            'features' => $this->faker->word(),
            'colors' => $this->faker->hexColor(),
        ];
    }
}
