<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'price' => $this->faker->randomFloat(),
            'description' => $this->faker->paragraph(),
            'stock' => $this->faker->randomDigit(),
            'category_id' => $this->faker->randomElement([1, 2, 3]),
        ];
    }
}
