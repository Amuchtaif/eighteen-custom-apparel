<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $roles = Role::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('roles.index', compact('roles', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $role = Role::create($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Role berhasil ditambahkan.',
                'data' => $role,
            ], 201);
        }

        return redirect()->route('settings.roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    /**
     * Return the specified resource for editing (JSON).
     */
    public function edit(Role $role)
    {
        if (request()->expectsJson()) {
            return response()->json($role);
        }
        return redirect()->route('settings.roles.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $role->update($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Role berhasil diperbarui.',
                'data' => $role,
            ]);
        }

        return redirect()->route('settings.roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Safety check: check if any users are assigned this role
        if ($role->users()->count() > 0) {
            $msg = 'Tidak dapat menghapus peran ini karena sedang digunakan oleh pengguna sistem.';
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $role->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dihapus.',
            ]);
        }

        return redirect()->route('settings.roles.index')->with('success', 'Role berhasil dihapus.');
    }
}
