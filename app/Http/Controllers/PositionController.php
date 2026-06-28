<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $positions = Position::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('positions.index', compact('positions', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:positions,name',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $position = Position::create($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil ditambahkan.',
                'data' => $position,
            ], 201);
        }

        return redirect()->route('settings.positions.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    /**
     * Return the specified resource for editing (JSON).
     */
    public function edit(Position $position)
    {
        if (request()->expectsJson()) {
            return response()->json($position);
        }
        return redirect()->route('settings.positions.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:positions,name,' . $position->id,
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $position->update($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil diperbarui.',
                'data' => $position,
            ]);
        }

        return redirect()->route('settings.positions.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        // Safety check: check if any employees are assigned this position
        if ($position->employees()->count() > 0) {
            $msg = 'Tidak dapat menghapus jabatan ini karena sedang digunakan oleh karyawan.';
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $position->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil dihapus.',
            ]);
        }

        return redirect()->route('settings.positions.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}
