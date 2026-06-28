<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Office;
use App\Models\WorkShift;
use App\Models\Employee;

class AttendanceRefactorSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Offices
        $produksi = Office::firstOrCreate(
            ['name' => 'Kantor Produksi'],
            [
                'address' => 'Jl. Produksi No. 1',
                'latitude' => -6.200000,
                'longitude' => 106.816666,
                'radius_meter' => 100,
                'is_active' => true,
            ]
        );

        $studio = Office::firstOrCreate(
            ['name' => 'Kantor Studio'],
            [
                'address' => 'Jl. Studio No. 2',
                'latitude' => -6.210000,
                'longitude' => 106.820000,
                'radius_meter' => 100,
                'is_active' => true,
            ]
        );

        // 2. Create Shifts
        $reguler = WorkShift::firstOrCreate(
            ['name' => 'Shift Reguler'],
            [
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'late_tolerance_minutes' => 15,
                'is_active' => true,
            ]
        );

        $produksiShift = WorkShift::firstOrCreate(
            ['name' => 'Shift Produksi'],
            [
                'start_time' => '07:00:00',
                'end_time' => '16:00:00',
                'late_tolerance_minutes' => 15,
                'is_active' => true,
            ]
        );

        // 3. Assign Default Office and Shift to all existing Employees
        Employee::query()->update([
            'office_id' => $produksi->id,
            'work_shift_id' => $reguler->id,
        ]);
    }
}
