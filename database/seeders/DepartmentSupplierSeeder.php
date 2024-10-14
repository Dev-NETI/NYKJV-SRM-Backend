<?php

namespace Database\Seeders;

use App\Models\DepartmentSupplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DepartmentSupplier::truncate();
        
        for($x = 0 ; $x <= 50 ; $x++){
            DepartmentSupplier::create([
                    'department_id' => rand(1, 4),
                    'supplier_id' => rand(1, 20),
                ]);
        }
    }
}
