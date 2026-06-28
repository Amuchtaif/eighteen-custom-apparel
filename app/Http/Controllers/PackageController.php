<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $packages = Package::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->withCount('orders')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('packages.index', compact('packages', 'search'));
    }

    /**
     * Show the specified resource (JSON for modal detail).
     */
    public function show(Package $package)
    {
        $package->loadCount('orders');

        if (request()->expectsJson()) {
            return response()->json($package);
        }

        return redirect()->route('packages.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:packages,name|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->only('name', 'description');
        $data['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;

        $package = Package::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Paket berhasil dibuat.',
                'data' => $package,
            ], 201);
        }

        return redirect()->route('packages.index')
            ->with('success', 'Paket berhasil dibuat.');
    }

    /**
     * Return the specified resource data for editing (JSON).
     */
    public function edit(Package $package)
    {
        if (request()->expectsJson()) {
            return response()->json($package);
        }

        return redirect()->route('packages.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:packages,name,' . $package->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->only('name', 'description');
        $data['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : $package->is_active;

        $package->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Paket berhasil diperbarui.',
                'data' => $package,
            ]);
        }

        return redirect()->route('packages.index')
            ->with('success', 'Paket berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        if ($package->orders()->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus paket. Paket ini memiliki pesanan aktif.',
                ], 422);
            }
            return back()->with('error', 'Tidak dapat menghapus paket. Paket ini memiliki pesanan aktif.');
        }

        $package->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Paket berhasil dihapus.',
            ]);
        }

        return redirect()->route('packages.index')
            ->with('success', 'Paket berhasil dihapus.');
    }
}
