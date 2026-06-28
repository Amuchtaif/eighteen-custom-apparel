<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $departments = Department::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('departments.index', compact('departments', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $department = Department::create($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Departemen berhasil ditambahkan.',
                'data' => $department,
            ], 201);
        }

        return redirect()->route('settings.departments.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    /**
     * Return the specified resource for editing (JSON).
     */
    public function edit(Department $department)
    {
        if (request()->expectsJson()) {
            return response()->json($department);
        }
        return redirect()->route('settings.departments.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $department->update($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Departemen berhasil diperbarui.',
                'data' => $department,
            ]);
        }

        return redirect()->route('settings.departments.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        // Safety check: check if any employees are assigned this department
        if ($department->employees()->count() > 0) {
            $msg = 'Tidak dapat menghapus departemen ini karena sedang digunakan oleh karyawan.';
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $department->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Departemen berhasil dihapus.',
            ]);
        }

        return redirect()->route('settings.departments.index')->with('success', 'Departemen berhasil dihapus.');
    }
}
