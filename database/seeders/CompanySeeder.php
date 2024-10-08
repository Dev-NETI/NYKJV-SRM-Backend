<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Company::truncate();
        $data = [
            0 => ['NYK-Fil Maritime E-Training, Inc.'],
            1 => ['NYK-Fil Ship Management, Inc.'],
            2 => ['NYK-TDG Maritime Academy'],
        ];

        foreach($data as $index=>[$name]){
            Company::create([
                'name' => $name,
            ]);
        }
    }
}
