<?php

namespace Database\Seeders;

use App\Models\Products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Products::truncate();

        for ($i = 1; $i <= 300; $i++) {
            DB::table('products')->insert([
                'slug' => encrypt($i),
                'supplier_id' => rand(1, 20),
                'category_id' => rand(1, 3),
                'brand_id' => rand(1, 4),
                'name' => fake()->name(),
                'price' => rand(100, 1000),
                'specification' => fake()->paragraph(),
                'is_active' => true,
                'modified_by' => 'Seeder Script',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
