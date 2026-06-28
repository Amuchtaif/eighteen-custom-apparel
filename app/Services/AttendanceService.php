<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class AttendanceService
{
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): int
    {
        $earthRadius = 6371000; // in meters

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return (int) round($earthRadius * $c);
    }

    public function checkIn(int $employeeId, float $lat, float $lng, ?string $selfiePath = null, ?string $notes = null, ?int $officeId = null): Attendance
    {
        return DB::transaction(function () use ($employeeId, $lat, $lng, $selfiePath, $notes, $officeId) {
            $employee = Employee::with(['office', 'workShift'])->findOrFail($employeeId);

            $office = null;
            if ($officeId) {
                $office = \App\Models\Office::find($officeId);
            }
            if (!$office) {
                $office = $employee->office;
            }

            if (!$office || !$employee->workShift) {
                throw new Exception("Karyawan belum memiliki penempatan Lokasi atau Shift Kerja.");
            }

            // Calculate Distance
            $distance = $this->calculateDistance($lat, $lng, $office->latitude, $office->longitude);

            if ($distance > $office->radius_meter && config('services.attendance.bypass_geofence') !== true) {
                throw new Exception("Anda berada di luar radius kantor {$office->name} ({$distance}m / {$office->radius_meter}m).");
            }

            $today = now()->toDateString();
            $existing = Attendance::where('employee_id', $employeeId)
                ->whereDate('check_in_time', $today)
                ->first();

            if ($existing) {
                throw new Exception("Anda sudah melakukan absen masuk hari ini.");
            }

            // Check late status
            $now = now();
            $shiftStart = Carbon::parse($employee->workShift->start_time);
            $shiftStart->setDate($now->year, $now->month, $now->day);
            $lateTolerance = $employee->workShift->late_tolerance_minutes;

            $status = 'Hadir';
            if ($now->greaterThan($shiftStart->copy()->addMinutes($lateTolerance))) {
                $status = 'Terlambat';
            }

            // Save attendance
            $attendance = Attendance::create([
                'employee_id' => $employeeId,
                'office_id' => $employee->office_id,
                'work_shift_id' => $employee->work_shift_id,
                'check_in_time' => now(),
                'check_in_latitude' => $lat,
                'check_in_longitude' => $lng,
                'check_in_distance' => $distance,
                'check_in_photo' => $selfiePath,
                'attendance_status' => $status,
                'notes' => $notes,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => $employee->user_id,
                'module' => 'Attendance',
                'action' => 'Check In',
                'description' => "Employee {$employee->employee_code} checked in at lat: {$lat}, lng: {$lng}. Distance: {$distance}m.",
                'ip_address' => request()->ip(),
            ]);

            return $attendance;
        });
    }

    public function checkOut(int $employeeId, float $lat, float $lng, ?string $selfiePath = null, ?string $notes = null, ?int $officeId = null): Attendance
    {
        return DB::transaction(function () use ($employeeId, $lat, $lng, $selfiePath, $notes, $officeId) {
            $employee = Employee::with('office')->findOrFail($employeeId);
            $today = now()->toDateString();

            $attendance = Attendance::where('employee_id', $employeeId)
                ->whereDate('check_in_time', $today)
                ->whereNull('check_out_time')
                ->first();

            if (!$attendance) {
                throw new Exception("Tidak ditemukan sesi absen masuk yang aktif untuk hari ini.");
            }

            $office = null;
            if ($officeId) {
                $office = \App\Models\Office::find($officeId);
            }
            if (!$office) {
                $office = \App\Models\Office::find($attendance->office_id) ?? $employee->office;
            }

            if (!$office) {
                throw new Exception("Karyawan belum memiliki penempatan Lokasi Kantor.");
            }

            $distance = $this->calculateDistance($lat, $lng, $office->latitude, $office->longitude);

            if ($distance > $office->radius_meter && config('services.attendance.bypass_geofence') !== true) {
                throw new Exception("Anda berada di luar radius kantor {$office->name} ({$distance}m / {$office->radius_meter}m).");
            }

            $attendance->update([
                'check_out_time' => now(),
                'check_out_latitude' => $lat,
                'check_out_longitude' => $lng,
                'check_out_distance' => $distance,
                'check_out_photo' => $selfiePath,
                'notes' => $attendance->notes . ($notes ? " | Checkout: " . $notes : ""),
            ]);

            ActivityLog::create([
                'user_id' => $employee->user_id,
                'module' => 'Attendance',
                'action' => 'Check Out',
                'description' => "Employee {$employee->employee_code} checked out at lat: {$lat}, lng: {$lng}. Distance: {$distance}m.",
                'ip_address' => request()->ip(),
            ]);

            return $attendance;
        });
    }
}
