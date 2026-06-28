<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Exception;

class AttendanceController extends Controller
{
    /**
     * Display administrative list of daily attendance logs.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $attendances = Attendance::query()
            ->with('employee.user')
            ->when($search, function ($query, $search) {
                $query->whereHas('employee', function ($eq) use ($search) {
                    $eq->where('employee_code', 'like', "%{$search}%")
                       ->orWhereHas('user', function ($uq) use ($search) {
                           $uq->where('name', 'like', "%{$search}%");
                       });
                });
            })
            ->orderBy('check_in_time', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('attendances.index', compact('attendances', 'search'));
    }



    /**
     * Handle clock-in/out form submission
     */
    public function clock(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'employee_code' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'action' => 'required|in:check_in,check_out',
            'selfie_file' => 'required|image|max:2048', // 2MB Max
            'notes' => 'nullable|string|max:255',
            'office_id' => 'nullable|exists:offices,id',
        ]);

        $employeeId = $request->input('employee_id');
        $code = $request->input('employee_code');
        $lat = (float) $request->input('latitude');
        $lng = (float) $request->input('longitude');
        $notes = $request->input('notes');
        $action = $request->input('action');
        $officeId = $request->input('office_id') ? (int) $request->input('office_id') : null;

        try {
            $attendanceService = app(AttendanceService::class);

            if (!$request->hasFile('selfie_file')) {
                throw new Exception("Foto selfie wajib dilampirkan untuk validasi absensi.");
            }
            $path = $request->file('selfie_file')->store('attendance', 'public');

            if ($action === 'check_in') {
                $attendanceService->checkIn($employeeId, $lat, $lng, $path, $notes, $officeId);
                $msg = "Sukses! Berhasil melakukan absen masuk.";
            } else {
                $attendanceService->checkOut($employeeId, $lat, $lng, $path, $notes, $officeId);
                $msg = "Sukses! Berhasil melakukan absen keluar.";
            }

            return redirect()->route('employee.dashboard')
                ->with('success_message', $msg);

        } catch (Exception $e) {
            return back()->withInput()
                ->with('error_message', $e->getMessage());
        }
    }
}
