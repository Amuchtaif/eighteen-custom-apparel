<?php

namespace App\Http\Controllers;

use App\Models\WorkShift;
use Illuminate\Http\Request;

class WorkShiftController extends Controller
{
    public function index()
    {
        $shifts = WorkShift::paginate(10);
        return view('work-shifts.index', compact('shifts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'late_tolerance_minutes' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        WorkShift::create($validated);

        return redirect()->route('work-shifts.index')->with('success', 'Shift Kerja berhasil ditambahkan.');
    }

    public function update(Request $request, WorkShift $workShift)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i:s,H:i',
            'end_time' => 'required|date_format:H:i:s,H:i',
            'late_tolerance_minutes' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $workShift->update($validated);

        return redirect()->route('work-shifts.index')->with('success', 'Shift Kerja berhasil diperbarui.');
    }

    public function destroy(WorkShift $workShift)
    {
        if ($workShift->employees()->count() > 0) {
            return redirect()->route('work-shifts.index')->with('error', 'Gagal menghapus Shift Kerja karena masih digunakan oleh pegawai.');
        }

        $workShift->delete();

        return redirect()->route('work-shifts.index')->with('success', 'Shift Kerja berhasil dihapus.');
    }
}
