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
            0 => ["Accreditation Form", 1],
            1 => ["Company's Form", 1],
            2 => ["List of Products/Services", 1],
            3 => ["List of Clients", 1],
            4 => ["BIR Registration", 1],
            5 => ["Business Permit", 1],
        ];

        foreach($data as $index=>[$name, $documentTypeCategoryId]){
            DocumentType::create([
                    'name' => $name,
                    'document_type_category_id' => $documentTypeCategoryId,
                ]);
        }
    }
}
