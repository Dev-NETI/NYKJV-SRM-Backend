<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Supplier::truncate();

        for ($x = 0; $x < 5; $x++) {
            Supplier::create([
                'name' => fake()->domainWord(),
                'street_address' => fake()->streetAddress(),
            ]);
        }
    }
}
