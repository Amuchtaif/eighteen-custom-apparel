<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'description' => 'Super Administrator with full access', 'is_active' => true],
            ['name' => 'Owner', 'description' => 'Business Owner with access to reports and monitoring', 'is_active' => true],
            ['name' => 'Admin', 'description' => 'Administrator with partial access', 'is_active' => true],
            ['name' => 'Sales', 'description' => 'Sales staff managing customers, quotations, and orders', 'is_active' => true],
            ['name' => 'Production', 'description' => 'Production staff tracking apparel design and manufacturing stages', 'is_active' => true],
            ['name' => 'Produksi', 'description' => 'Staf Produksi melacak desain pakaian dan tahapan manufaktur', 'is_active' => true],
            ['name' => 'HRD', 'description' => 'Human Resources Department managing employees and attendance', 'is_active' => true],
            ['name' => 'Finance', 'description' => 'Finance staff managing invoices and payment approvals', 'is_active' => true],
            ['name' => 'Keuangan', 'description' => 'Staf Keuangan mengelola faktur dan persetujuan pembayaran', 'is_active' => true],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
