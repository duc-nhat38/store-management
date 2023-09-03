<?php

namespace Database\Seeders;

use App\Enums\ProductStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryIds = \App\Models\Category::pluck('id')->toArray();
        $trademarkIds = \App\Models\Trademark::pluck('id')->toArray();
        $storeIds = \App\Models\Store::pluck('id')->toArray();

        for ($i = 1; $i <= 100; $i++) {
            $product = \App\Models\Product::create([
                'code' => "P" . \Illuminate\Support\Str::padLeft($i, 10, 0),
                'name' => "Product {$i}",
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'trademark_id' => $trademarkIds[array_rand($trademarkIds)],
                'quantity' => rand(1000, 10000),
                'price' => rand(10000, 10000000),
                'currency' => 'VND',
                'origin' => 'China',
                'status' => rand(ProductStatus::UNAVAILABLE, ProductStatus::AVAILABLE),
                'description' => "Product {$i} description.",
            ]);

            $product->stores()->sync(array_map(fn ($index) => $storeIds[$index], array_rand($storeIds, 3)));
        }
    }
}
