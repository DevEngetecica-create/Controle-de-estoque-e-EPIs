<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Subcategory;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::all()->each(function ($brand) {
            Product::factory()->count(50)->create([
                'brand_id' => $brand->id,
                'category_id' => function() {
                    return Subcategory::inRandomOrder()->first()->category_id;
                },
                'subcategory_id' => function() {
                    return Subcategory::inRandomOrder()->first()->id;
                },
            ]);
        });
    }
}
