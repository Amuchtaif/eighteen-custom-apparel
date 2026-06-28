<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Office;
use App\Models\WorkShift;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    /**
     * Display the mobile employee dashboard for the authenticated user.
     */
    public function dashboard()
    {
        $user = auth()->user();
        $employee = Employee::with(['user', 'office'])->where('user_id', $user->id)->first();

        if (!$employee) {
            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Profil karyawan tidak ditemukan untuk akun Anda. Silakan hubungi HRD/Admin.');
        }

        // Today's attendance (most recent check_in today)
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('check_in_time', Carbon::today())
            ->orderBy('check_in_time', 'desc')
            ->first();

        // Calculate hours worked today
        $hoursWorked = 0;
        $overtimeHours = 0;
        $progressPercent = 0;

        if ($todayAttendance) {
            $checkIn = $todayAttendance->check_in_time;
            $checkOut = $todayAttendance->check_out_time ?? Carbon::now();
            $hoursWorked = round($checkIn->diffInMinutes($checkOut) / 60, 1);

            // Overtime is anything above 8 hours
            $overtimeHours = max(0, round($hoursWorked - 8, 1));
            $progressPercent = min(100, round(($hoursWorked / 8) * 100));
        }

        // Recent attendance logs (last 5 entries)
        $recentLogs = Attendance::where('employee_id', $employee->id)
            ->orderBy('check_in_time', 'desc')
            ->take(5)
            ->get();

        // Get active offices
        $offices = Office::where('is_active', true)->orderBy('name')->get();

        return view('employees.dashboard', compact(
            'employee',
            'todayAttendance',
            'hoursWorked',
            'overtimeHours',
            'progressPercent',
            'recentLogs',
            'offices'
        ));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $employees = Employee::query()
            ->with(['user', 'position', 'department'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('employee_code', 'like', "%{$search}%")
                      ->orWhereHas('position', function ($pq) use ($search) {
                          $pq->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('department', function ($dq) use ($search) {
                          $dq->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('user', function ($uq) use ($search) {
                          $uq->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->orderBy('employee_code')
            ->paginate(10)
            ->withQueryString();

        return view('employees.index', compact('employees', 'search'));
    }

    /**
     * Show the specified resource (JSON for modal detail).
     */
    public function show(Employee $employee)
    {
        $employee->load(['user', 'position', 'department']);

        if (request()->expectsJson()) {
            return response()->json($employee);
        }

        return redirect()->route('employees.index');
    }

    /**
     * Return data needed for creating a new employee (JSON).
     */
    public function create()
    {
        $roles = \App\Models\Role::where('is_active', true)->where('name', '!=', 'Customer')->orderBy('name')->get();
        $positions = \App\Models\Position::where('is_active', true)->orderBy('name')->get();
        $departments = \App\Models\Department::where('is_active', true)->orderBy('name')->get();
        $offices = Office::where('is_active', true)->get();
        $workShifts = WorkShift::where('is_active', true)->get();
        $employeeCode = Employee::generateEmployeeCode();

        if (request()->expectsJson()) {
            return response()->json([
                'roles' => $roles,
                'positions' => $positions,
                'departments' => $departments,
                'offices' => $offices,
                'workShifts' => $workShifts,
                'employee_code' => $employeeCode
            ]);
        }

        return redirect()->route('employees.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'join_date' => 'required|date',
            'status' => 'required|string|max:50',
            'office_id' => 'required|exists:offices,id',
            'work_shift_id' => 'required|exists:work_shifts,id',
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Hash::make($request->password),
                'role_id' => $request->role_id,
            ]);

            $employeeCode = Employee::generateEmployeeCode();

            $employee = Employee::create([
                'user_id' => $user->id,
                'employee_code' => $employeeCode,
                'role_id' => $request->role_id,
                'position_id' => $request->position_id,
                'department_id' => $request->department_id,
                'join_date' => $request->join_date,
                'status' => $request->status,
                'office_id' => $request->office_id,
                'work_shift_id' => $request->work_shift_id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil karyawan berhasil dibuat.',
                    'data' => $employee->load(['user', 'position', 'department']),
                ], 201);
            }

            return redirect()->route('employees.index')
                ->with('success', 'Profil karyawan berhasil dibuat.');
        });
    }

    /**
     * Return the specified resource data for editing (JSON).
     */
    public function edit(Employee $employee)
    {
        $employee->load(['user', 'position', 'department']);

        if (request()->expectsJson()) {
            $roles = \App\Models\Role::where('is_active', true)->where('name', '!=', 'Customer')->orderBy('name')->get();
            $positions = \App\Models\Position::where('is_active', true)->orderBy('name')->get();
            $departments = \App\Models\Department::where('is_active', true)->orderBy('name')->get();
            $offices = Office::where('is_active', true)->get();
            $workShifts = WorkShift::where('is_active', true)->get();

            return response()->json([
                'employee' => $employee,
                'roles' => $roles,
                'positions' => $positions,
                'departments' => $departments,
                'offices' => $offices,
                'workShifts' => $workShifts
            ]);
        }

        return redirect()->route('employees.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $employee->user_id,
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'join_date' => 'required|date',
            'status' => 'required|string|max:50',
            'office_id' => 'required|exists:offices,id',
            'work_shift_id' => 'required|exists:work_shifts,id',
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $employee) {
            $employee->update($request->only([
                'role_id', 'position_id', 'department_id', 'join_date', 'status', 'office_id', 'work_shift_id'
            ]));

            if ($employee->user) {
                $userData = [
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'role_id' => $request->input('role_id'),
                ];
                if ($request->filled('password')) {
                    $userData['password'] = \Hash::make($request->input('password'));
                }
                $employee->user->update($userData);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil karyawan berhasil diperbarui.',
                    'data' => $employee->load(['user', 'position', 'department']),
                ]);
            }

            return redirect()->route('employees.index')
                ->with('success', 'Profil karyawan berhasil diperbarui.');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        if ($employee->attendances()->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus karyawan. Karyawan memiliki catatan kehadiran aktif.',
                ], 422);
            }
            return back()->with('error', 'Tidak dapat menghapus karyawan. Karyawan memiliki catatan kehadiran aktif.');
        }

        // Store user_id before deleting employee
        $userId = $employee->user_id;
        
        $employee->delete();
        
        // Delete the associated user account
        if ($userId) {
            \App\Models\User::where('id', $userId)->delete();
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil karyawan berhasil dihapus.',
            ]);
        }

        return redirect()->route('employees.index')
            ->with('success', 'Profil karyawan berhasil dihapus.');
    }
}
