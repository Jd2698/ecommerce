<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Product;
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

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            $file = new File(['route' => '/storage/images/products/productDefault.png']);
            $product->file()->save($file);
        });
    }
}
