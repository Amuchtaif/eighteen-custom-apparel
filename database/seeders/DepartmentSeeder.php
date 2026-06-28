<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Sales', 'description' => 'Sales Department', 'is_active' => true],
            ['name' => 'Produksi', 'description' => 'Production Department', 'is_active' => true],
            ['name' => 'Marketing', 'description' => 'Marketing Department', 'is_active' => true],
            ['name' => 'Keuangan', 'description' => 'Finance Department', 'is_active' => true],
            ['name' => 'HRD', 'description' => 'Human Resources Department', 'is_active' => true],
            // Additional ones for compatibility
            ['name' => 'Production', 'description' => 'Production Department (English)', 'is_active' => true],
            ['name' => 'Human Resources', 'description' => 'Human Resources Department (English)', 'is_active' => true],
            ['name' => 'Finance', 'description' => 'Finance Department (English)', 'is_active' => true],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(['name' => $dept['name']], $dept);
        }
    }
}
