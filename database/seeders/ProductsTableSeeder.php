<?php

namespace Database\Seeders;

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
        // Inserting 10 sample products
        for ($i = 1; $i <= 10; $i++) {
            DB::table('products')->insert([
                'slug' => encrypt($i),
                'supplier_id' => rand(1, 5),  // Assuming suppliers with IDs 1-5 exist
                'category_id' => rand(1, 3),  // Assuming categories with IDs 1-3 exist
                'brand_id' => rand(1, 4),     // Assuming brands with IDs 1-4 exist
                'name' => 'Product '.$i,
                'price' => rand(100, 1000),   // Random price between 100 and 1000
                'specification' => 'Specification details for Product '.$i,
                'is_active' => true,
                'modified_by' => 'Seeder Script',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
