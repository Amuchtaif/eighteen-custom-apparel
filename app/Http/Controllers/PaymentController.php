<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $payments = Payment::query()
            ->with(['invoice.order.customer'])
            ->when($search, function ($query, $search) {
                $query->where('reference_number', 'like', "%{$search}%")
                      ->orWhereHas('invoice', function ($iq) use ($search) {
                          $iq->where('invoice_number', 'like', "%{$search}%")
                            ->orWhereHas('order.customer', function ($cq) use ($search) {
                                $cq->where('company_name', 'like', "%{$search}%");
                            });
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('payments.index', compact('payments', 'search'));
    }

    /**
     * Return data needed for creating a new payment (JSON).
     */
    public function create(Request $request)
    {
        $invoices = Invoice::whereIn('status', ['unpaid', 'partially_paid'])
            ->with('order.customer')
            ->orderBy('invoice_number', 'desc')
            ->get();

        $selectedInvoiceId = $request->input('invoice_id');
        $suggestedAmount = 0;

        if ($selectedInvoiceId) {
            $invoice = Invoice::find($selectedInvoiceId);
            if ($invoice) {
                $totalPaid = $invoice->payments()->sum('amount');
                $suggestedAmount = max(0, $invoice->total_amount - $totalPaid);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'invoices' => $invoices,
                'selected_invoice_id' => $selectedInvoiceId,
                'suggested_amount' => $suggestedAmount,
            ]);
        }

        return view('payments.create', compact('invoices', 'selectedInvoiceId', 'suggestedAmount'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|in:cash,bank_transfer,ewallet',
            'reference_number' => 'nullable|string|max:255',
            'proof_file' => 'nullable|file|image|max:2048',
            'notes' => 'nullable|string',
        ]);

        try {
            $data = $request->only('payment_date', 'amount', 'payment_method', 'reference_number', 'notes');

            if ($request->hasFile('proof_file')) {
                $path = $request->file('proof_file')->store('payments', 'public');
                $data['proof_file'] = $path;
            }

            $payment = app(PaymentService::class)->recordPayment($request->input('invoice_id'), $data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil dicatat.',
                    'data' => $payment,
                ], 201);
            }

            return redirect()->route('payments.index')
                ->with('success', 'Pembayaran berhasil dicatat.');
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
}
