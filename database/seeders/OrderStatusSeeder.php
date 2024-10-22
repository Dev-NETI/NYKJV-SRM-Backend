<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderStatus::truncate();

        $data = [
            0 => ["RFQ"],
            1 => ["Memo"],
            2 => ["PR"],
            3 => ["Delivery"],
            4 => ["RFP"],
        ];

        foreach($data as $index=>[$name]){
            OrderStatus::create([
                    'name' => $name,
                ]);
        }
    }
}
