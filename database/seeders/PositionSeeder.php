<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['name' => 'Sales Representative', 'description' => 'Sales Representative', 'is_active' => true],
            ['name' => 'Sales Manager', 'description' => 'Sales Manager', 'is_active' => true],
            ['name' => 'Operator Produksi', 'description' => 'Production Operator', 'is_active' => true],
            ['name' => 'Supervisor Produksi', 'description' => 'Production Supervisor', 'is_active' => true],
            ['name' => 'Admin Keuangan', 'description' => 'Finance Administrator', 'is_active' => true],
            ['name' => 'Staff HRD', 'description' => 'HRD Staff member', 'is_active' => true],
            // Additional ones for compatibility
            ['name' => 'Production Designer', 'description' => 'Production Designer', 'is_active' => true],
            ['name' => 'HR Specialist', 'description' => 'HR Specialist', 'is_active' => true],
            ['name' => 'Accountant', 'description' => 'Accountant', 'is_active' => true],
        ];

        foreach ($positions as $pos) {
            Position::updateOrCreate(['name' => $pos['name']], $pos);
        }
    }
}
