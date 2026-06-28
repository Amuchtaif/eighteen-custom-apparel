<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Position;
use App\Models\Department;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $ownerRole = Role::where('name', 'Owner')->first();
        $salesRole = Role::where('name', 'Sales')->first();
        $productionRole = Role::where('name', 'Production')->first();
        $hrdRole = Role::where('name', 'HRD')->first();
        $financeRole = Role::where('name', 'Finance')->first();

        // 1. Super Admin
        User::firstOrCreate(
            ['email' => 'admin@eighteen.com'],
            [
                'role_id' => $superAdminRole->id,
                'name' => 'Super Admin Eighteen',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // 2. Owner
        User::firstOrCreate(
            ['email' => 'owner@eighteen.com'],
            [
                'role_id' => $ownerRole->id,
                'name' => 'Owner Eighteen',
                'phone' => '081234567891',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // 3. Sales
        $salesUser = User::firstOrCreate(
            ['email' => 'sales@eighteen.com'],
            [
                'role_id' => $salesRole->id,
                'name' => 'Sales Staff',
                'phone' => '081234567892',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        Employee::firstOrCreate(
            ['user_id' => $salesUser->id],
            [
                'employee_code' => 'EMP-00001',
                'role_id' => $salesRole->id,
                'position_id' => Position::where('name', 'Sales Representative')->first()->id,
                'department_id' => Department::where('name', 'Sales')->first()->id,
                'join_date' => now()->subMonths(6),
                'status' => 'active',
            ]
        );

        // 4. Production
        $productionUser = User::firstOrCreate(
            ['email' => 'production@eighteen.com'],
            [
                'role_id' => $productionRole->id,
                'name' => 'Production Staff',
                'phone' => '081234567893',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        Employee::firstOrCreate(
            ['user_id' => $productionUser->id],
            [
                'employee_code' => 'EMP-00002',
                'role_id' => $productionRole->id,
                'position_id' => Position::where('name', 'Production Designer')->first()->id,
                'department_id' => Department::where('name', 'Production')->first()->id,
                'join_date' => now()->subMonths(6),
                'status' => 'active',
            ]
        );

        // 5. HRD
        $hrdUser = User::firstOrCreate(
            ['email' => 'hrd@eighteen.com'],
            [
                'role_id' => $hrdRole->id,
                'name' => 'HRD Staff',
                'phone' => '081234567894',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        Employee::firstOrCreate(
            ['user_id' => $hrdUser->id],
            [
                'employee_code' => 'EMP-00003',
                'role_id' => $hrdRole->id,
                'position_id' => Position::where('name', 'HR Specialist')->first()->id,
                'department_id' => Department::where('name', 'Human Resources')->first()->id,
                'join_date' => now()->subMonths(12),
                'status' => 'active',
            ]
        );

        // 6. Finance
        $financeUser = User::firstOrCreate(
            ['email' => 'finance@eighteen.com'],
            [
                'role_id' => $financeRole->id,
                'name' => 'Finance Staff',
                'phone' => '081234567895',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        Employee::firstOrCreate(
            ['user_id' => $financeUser->id],
            [
                'employee_code' => 'EMP-00004',
                'role_id' => $financeRole->id,
                'position_id' => Position::where('name', 'Accountant')->first()->id,
                'department_id' => Department::where('name', 'Finance')->first()->id,
                'join_date' => now()->subMonths(8),
                'status' => 'active',
            ]
        );

        // 7. Customer record (for order data, no login account)
        Customer::firstOrCreate(
            ['email' => 'customer@eighteen.com'],
            [
                'company_name' => 'FC Brotherhood Jersey',
                'contact_person' => 'John Doe',
                'phone' => '081234567896',
                'address' => 'Jl. Merdeka No. 18',
                'city' => 'Jakarta',
                'notes' => 'Regular jerseys orderer',
            ]
        );
    }
}
