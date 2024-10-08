<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Department::truncate();

        //test data
        $data = [
            0 => [1,"Network Operation Center"],
            1 => [2,"IT Department"],
            2 => [3,"Network Operation Center"],
            3 => [1,"Business Operation Department"],
        ];

        foreach($data as $index=>[$companyId,$name]){
                Department::create([
                    'company_id' => $companyId,
                    'name' => $name,
                ]);
        }

    }
}
