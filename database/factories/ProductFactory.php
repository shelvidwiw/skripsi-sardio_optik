<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = ucwords($this->faker->unique()->words(3, true));

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numberBetween(1, 100000),
            'category' => $this->faker->randomElement(Product::CATEGORIES),
            'brand' => $this->faker->randomElement(['Rayban', 'Oakley', 'X-Ceed', 'Cardinal', 'Softlens X2']),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->numberBetween(50, 2000) * 1000,
            'stock' => $this->faker->numberBetween(0, 50),
            'images' => [],
        ];
    }

    public function category(string $category): self
    {
        return $this->state(fn () => ['category' => $category]);
    }
}
