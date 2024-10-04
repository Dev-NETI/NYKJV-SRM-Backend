<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
