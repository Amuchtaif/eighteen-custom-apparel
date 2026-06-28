<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $customers = Customer::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('company_name', 'like', "%{$search}%")
                      ->orWhere('contact_person', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('company_name')
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', compact('customers', 'search'));
    }

    /**
     * Show the specified resource (JSON for modal detail).
     */
    public function show(Customer $customer)
    {
        if (request()->expectsJson()) {
            return response()->json($customer);
        }

        return redirect()->route('customers.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $customer = Customer::create($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pelanggan berhasil dibuat.',
                'data' => $customer,
            ], 201);
        }

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dibuat.');
    }

    /**
     * Return the specified resource data for editing (JSON).
     */
    public function edit(Customer $customer)
    {
        if (request()->expectsJson()) {
            return response()->json($customer);
        }

        return redirect()->route('customers.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $customer->update($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pelanggan berhasil diperbarui.',
                'data' => $customer,
            ]);
        }

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        if ($customer->orders()->count() > 0 || $customer->quotations()->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus pelanggan. Pelanggan memiliki penawaran atau pesanan aktif.',
                ], 422);
            }
            return back()->with('error', 'Tidak dapat menghapus pelanggan. Pelanggan memiliki penawaran atau pesanan aktif.');
        }

        $customer->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pelanggan berhasil dihapus.',
            ]);
        }

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
