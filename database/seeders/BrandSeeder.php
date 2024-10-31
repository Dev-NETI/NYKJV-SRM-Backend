<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Brand::truncate();

        for ($i = 1; $i <= 10; $i++) {
            DB::table('brands')->insert([
                'slug' => encrypt($i),
                'name' => 'Brand ' . $i,
                'is_active' => true,
                'modified_by' => 'Seeder Script',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
