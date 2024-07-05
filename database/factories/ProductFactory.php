<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Category;
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

    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'quantity' => $this->faker->numberBetween(1, 100),
            'unit_price' => $this->faker->randomFloat(2, 1, 100),
            'expiry_date' => $this->faker->date,
            'category_id' => Category::factory(),
            'subcategory_id' => Subcategory::factory(),
            'image' => $this->faker->imageUrl,
            'minimum_stock' => $this->faker->numberBetween(1, 10),
            'unit' => $this->faker->word,
            'brand_id' => Brand::factory(),
            'created_by' => $this->faker->email,
            'updated_by' => $this->faker->email,
        ];
    }
}
