<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'price' => fake()->numberBetween(15000, 150000),
            'stock' => fake()->numberBetween(10, 100),
            'is_bestseller' => fake()->boolean(30),
            'is_new' => fake()->boolean(30),
            'is_active' => true,
            'rating' => fake()->numberBetween(3, 5),
            'review_count' => fake()->numberBetween(0, 50),
        ];
    }
}
