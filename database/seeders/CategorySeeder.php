<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        $categories = [
            'Accessories',
            'Antivirus Software',
            'Backup Solutions',
            'Cabling & Wiring',
            'Cloud Subscriptions',
            'CRM Tools',
            'Database Systems',
            'Desktops',
            'Development Tools',
            'Email Servers',
            'ERP Systems',
            'Firewalls',
            'Graphic Design Tools',
            'IoT Devices',
            'Laptops',
            'Mobile Devices',
            'Monitoring Tools',
            'Network Equipment',
            'Peripherals',
            'Power Supplies & UPS',
            'Printers & Scanners',
            'Project Management Software',
            'Security Devices',
            'Servers',
            'Software Licenses',
            'Storage Devices',
            'Video Conferencing Systems',
            'Virtual Machines',
            'VoIP Phones',
            'Workstations'
        ];

        foreach ($categories as $key => $category) {
            DB::table('categories')->insert([
                'slug' => encrypt($key + 1), // Encrypting unique numeric ID
                'name' => $category,
                'is_active' => true,
                'modified_by' => 'NOC',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
