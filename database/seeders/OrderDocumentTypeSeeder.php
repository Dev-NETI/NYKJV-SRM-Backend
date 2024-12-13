<?php

namespace Database\Seeders;

use App\Models\OrderDocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderDocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        OrderDocumentType::truncate();

        $data = [
            0 => ['Quotation'],
            1 => ['Sales Invoice'],
            2 => ['Delivery Receipt'],
        ];

        foreach ($data as $index => [$name]) {
            OrderDocumentType::create([
                'name' => $name
            ]);
        }
    }
}
