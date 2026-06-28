<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $invoices = Invoice::query()
            ->with(['order.customer'])
            ->when($search, function ($query, $search) {
                $query->where('invoice_number', 'like', "%{$search}%")
                      ->orWhereHas('order.customer', function ($cq) use ($search) {
                          $cq->where('company_name', 'like', "%{$search}%");
                      });
            })
            ->orderBy('invoice_number', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('invoices.index', compact('invoices', 'search'));
    }

    /**
     * Return data needed for creating a new invoice (JSON).
     */
    public function create()
    {
        $orders = Order::where('status_produksi', '!=', 'Selesai')
            ->whereDoesntHave('invoices', function ($q) {
                $q->where('status', '!=', 'cancelled');
            })
            ->with('customer')
            ->orderBy('kode_order', 'desc')
            ->get();

        if (request()->expectsJson()) {
            return response()->json(['orders' => $orders]);
        }

        return view('invoices.create', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        try {
            $invoice = app(PaymentService::class)->createInvoiceForOrder($request->input('order_id'));

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Faktur {$invoice->invoice_number} berhasil dibuat.",
                    'data' => $invoice->load('order.customer'),
                ], 201);
            }

            return redirect()->route('invoices.index')
                ->with('success', "Faktur {$invoice->invoice_number} berhasil dibuat.");
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['order.customer', 'payments']);
        $totalPaid = $invoice->payments()->sum('amount');
        $remainingBalance = max(0, $invoice->total_amount - $totalPaid);

        if (request()->expectsJson()) {
            return response()->json([
                'invoice' => $invoice,
                'total_paid' => $totalPaid,
                'remaining_balance' => $remainingBalance,
            ]);
        }

        return view('invoices.show', compact('invoice', 'totalPaid', 'remainingBalance'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus faktur yang sudah lunas.',
                ], 422);
            }
            return back()->with('error', 'Tidak dapat menghapus faktur yang sudah lunas.');
        }

        try {
            DB::transaction(function () use ($invoice) {
                $invoice->payments()->delete();
                $invoice->delete();
            });

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Faktur berhasil dihapus.',
                ]);
            }

            return redirect()->route('invoices.index')
                ->with('success', 'Faktur berhasil dihapus.');
        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->with('error', $e->getMessage());
        }
    }
}
