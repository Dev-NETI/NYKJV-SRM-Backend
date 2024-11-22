<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DocumentTypeCategory;

class DocumentTypeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Compliance'],
            ['name' => 'Contracts'],
            ['name' => 'Others'],
            ['name' => 'HR Documents'],
            ['name' => 'Technical Documents'],
        ];

        foreach ($categories as $category) {
            DocumentTypeCategory::create($category);
        }
    }
}
