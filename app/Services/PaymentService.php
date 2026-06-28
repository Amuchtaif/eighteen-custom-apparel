<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Order;
use App\Models\CustomerHistory;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Exception;

class PaymentService
{
    /**
     * Generate invoice number format: INV-YYYYMM-00001
     */
    public function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . now()->format('Ym') . '-';
        $count = Invoice::where('invoice_number', 'like', $prefix . '%')->count();
        return $prefix . str_pad($count + 1, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Create invoice for order
     */
    public function createInvoiceForOrder(int $orderId): Invoice
    {
        return DB::transaction(function () use ($orderId) {
            $order = Order::findOrFail($orderId);

            // Check if active invoice already exists for this order
            $existing = Invoice::where('order_id', $orderId)->where('status', '!=', 'cancelled')->first();
            if ($existing) {
                return $existing;
            }

            $invoice = Invoice::create([
                'order_id' => $orderId,
                'invoice_number' => $this->generateInvoiceNumber(),
                'invoice_date' => now()->toDateString(),
                'due_date' => now()->addDays(14)->toDateString(),
                'total_amount' => $order->grand_total,
                'status' => 'unpaid',
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id() ?? UserSeederHelper::getFirstAdminId(),
                'module' => 'Finance',
                'action' => 'Invoice Created',
                'description' => "Invoice {$invoice->invoice_number} created for order {$order->kode_order}.",
                'ip_address' => request()->ip(),
            ]);

            return $invoice;
        });
    }

    /**
     * Record a Payment
     */
    public function recordPayment(int $invoiceId, array $data): Payment
    {
        return DB::transaction(function () use ($invoiceId, $data) {
            $invoice = Invoice::findOrFail($invoiceId);
            $order = $invoice->order;

            $payment = Payment::create([
                'invoice_id' => $invoiceId,
                'payment_date' => $data['payment_date'] ?? now()->toDateString(),
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'reference_number' => $data['reference_number'] ?? null,
                'proof_file' => $data['proof_file'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // Recalculate Invoice status
            $totalPaid = $invoice->payments()->sum('amount');
            $newStatus = 'unpaid';
            if ($totalPaid >= $invoice->total_amount) {
                $newStatus = 'paid';
            } elseif ($totalPaid > 0) {
                // If it's roughly half or some down payment, it's partially paid
                $newStatus = 'partially_paid';
            }

            $invoice->update(['status' => $newStatus]);

            $userId = auth()->id() ?? UserSeederHelper::getFirstAdminId();

            // Log customer history
            CustomerHistory::create([
                'customer_id' => $order->customer_id,
                'user_id' => $userId,
                'activity' => 'Payment Recorded',
                'notes' => "Payment amount: {$payment->amount} for Invoice {$invoice->invoice_number}. Status is now: {$newStatus}",
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => $userId,
                'module' => 'Finance',
                'action' => 'Payment Recorded',
                'description' => "Payment recorded for Invoice {$invoice->invoice_number}, amount {$payment->amount}",
                'ip_address' => request()->ip(),
            ]);

            return $payment;
        });
    }
}
