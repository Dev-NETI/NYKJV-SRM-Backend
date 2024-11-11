<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Disable foreign key checks
        Role::truncate();                           // Truncate the roles table
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Re-enable foreign key checks

        $roles = [
            ['name' => 'Dashboard', 'url' => 'dashboard', 'is_active' => 1],
            ['name' => 'Category', 'url' => 'category', 'is_active' => 1],
            ['name' => 'Brand', 'url' => 'brand', 'is_active' => 1],
            ['name' => 'Product', 'url' => 'product', 'is_active' => 1],
            ['name' => 'Supplier Document', 'url' => 'supplier-document', 'is_active' => 1],
            ['name' => 'User Management', 'url' => 'user-management', 'is_active' => 1],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
