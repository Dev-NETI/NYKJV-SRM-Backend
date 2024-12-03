<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::truncate();
        $productId = [
            0 => [10],
            1 => [35],
            2 => [48],
            3 => [65],
            4 => [101],
            5 => [111],
        ];
        foreach ($productId as $index => [$productId]) {
            Order::create([
                'reference_number' => '202410220847001',
                'product_id' => $productId,
                'quantity' => 1,
                'order_status_id' => 1,
                'supplier_id' => 14,
                'created_by' => 'NETI Procurement Staff',
            ]);
        }
    }
}
