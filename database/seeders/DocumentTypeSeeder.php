<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DocumentType::truncate();

        //test data
        $data = [
            0 => [1,"Compliance Documents"],
            1 => [2,"Certification"],
            2 => [3,"Company Profile"],
            3 => [1,"Service Level Agreements"],
        ];

        foreach($data as $index=>[$companyId,$name]){
            DocumentType::create([
                    'name' => $name,
                ]);
        }
    }
}
