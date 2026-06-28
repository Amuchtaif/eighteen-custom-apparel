<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfficeController extends Controller
{
    public function index()
    {
        $offices = Office::paginate(10);
        return view('offices.index', compact('offices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Office::create($validated);

        return redirect()->route('offices.index')->with('success', 'Lokasi Kantor berhasil ditambahkan.');
    }

    public function update(Request $request, Office $office)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $office->update($validated);

        return redirect()->route('offices.index')->with('success', 'Lokasi Kantor berhasil diperbarui.');
    }

    public function destroy(Office $office)
    {
        // Add protection if needed (e.g. check if employees belong to this office)
        if ($office->employees()->count() > 0) {
            return redirect()->route('offices.index')->with('error', 'Gagal menghapus Lokasi Kantor karena masih memiliki pegawai.');
        }

        $office->delete();

        return redirect()->route('offices.index')->with('success', 'Lokasi Kantor berhasil dihapus.');
    }
}
