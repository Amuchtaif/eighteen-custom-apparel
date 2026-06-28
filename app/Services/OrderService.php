<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderPlayer;
use App\Models\DesignFile;
use App\Models\CustomerHistory;
use App\Models\ProductionStage;
use App\Models\ProductionLog;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Generate order number format: ORD-YYYYMM-0001
     */
    public function generateOrderNumber(): string
    {
        $prefix = 'ORD-' . now()->format('Ym') . '-';
        $count = Order::where('kode_order', 'like', $prefix . '%')->count();
        return $prefix . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new order directly
     */
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $data['kode_order'] = $this->generateOrderNumber();
            $data['status_produksi'] = $data['status_produksi'] ?? 'Antrian';

            $order = Order::create($data);

            // Sync invoice & payment automatically
            $this->syncInvoiceAndPayment($order);

            $creatorId = auth()->id() ?? UserSeederHelper::getFirstAdminId();

            // Set Initial Production Stage (Antrian)
            $antrianStage = ProductionStage::where('name', 'Antrian')->first();
            if ($antrianStage) {
                ProductionLog::create([
                    'order_id' => $order->id,
                    'stage_id' => $antrianStage->id,
                    'updated_by' => $creatorId,
                    'notes' => 'Pesanan dimasukkan ke antrean produksi.',
                    'started_at' => now(),
                ]);
            }

            // Log customer history
            CustomerHistory::create([
                'customer_id' => $order->customer_id,
                'user_id' => $creatorId,
                'activity' => 'Order Created Direct',
                'notes' => "Nomor pesanan: {$order->kode_order}",
            ]);

            return $order;
        });
    }

    /**
     * Add Design File to Order
     */
    public function uploadDesignFile(Order $order, string $filePath, string $fileName, ?string $notes = null): DesignFile
    {
        return DB::transaction(function () use ($order, $filePath, $fileName, $notes) {
            $uploaderId = auth()->id() ?? UserSeederHelper::getFirstAdminId();
            
            // Calculate next version
            $latestVersion = DesignFile::where('order_id', $order->id)->latest()->first();
            $nextVersion = 'v1';
            if ($latestVersion) {
                $versionNum = (int) str_replace('v', '', $latestVersion->version);
                $nextVersion = 'v' . ($versionNum + 1);
            }

            $designFile = DesignFile::create([
                'order_id' => $order->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'version' => $nextVersion,
                'notes' => $notes,
                'uploaded_by' => $uploaderId,
            ]);

            return $designFile;
        });
    }

    /**
     * Update Player List for Order
     */
    public function updatePlayerList(int $orderId, array $players): void
    {
        DB::transaction(function () use ($orderId, $players) {
            $order = Order::findOrFail($orderId);
            
            // Clear existing players
            OrderPlayer::where('order_id', $orderId)->delete();

            // Insert new players list
            foreach ($players as $player) {
                OrderPlayer::create([
                    'order_id' => $orderId,
                    'player_name' => $player['player_name'],
                    'player_number' => $player['player_number'] ?? null,
                    'size' => $player['size'],
                    'notes' => $player['notes'] ?? null,
                ]);
            }
        });
    }

    /**
     * Synchronize Invoice and Payments to stay consistent with Order totals and DP
     */
    public function syncInvoiceAndPayment(Order $order): void
    {
        DB::transaction(function () use ($order) {
            // 1. Ensure invoice exists and has correct total_amount
            $invoice = $order->invoices()->where('status', '!=', 'cancelled')->first();
            if (!$invoice) {
                $invoice = app(PaymentService::class)->createInvoiceForOrder($order->id);
            } else {
                $invoice->update(['total_amount' => $order->total_tagihan]);
            }

            // 2. Manage DP payment
            if ($order->dp > 0) {
                // Check if a DP payment exists
                $payment = $invoice->payments()
                    ->where('reference_number', 'like', 'DP-%')
                    ->first();
                if ($payment) {
                    $payment->update([
                        'amount' => $order->dp,
                    ]);
                } else {
                    $invoice->payments()->create([
                        'payment_date' => now()->toDateString(),
                        'amount' => $order->dp,
                        'payment_method' => 'bank_transfer',
                        'reference_number' => 'DP-' . $order->kode_order,
                        'notes' => 'Pembayaran Uang Muka (DP)',
                    ]);
                }
            } else {
                // If DP is 0, delete any auto-created DP payment
                $invoice->payments()
                    ->where('reference_number', 'like', 'DP-%')
                    ->delete();
            }

            // 3. Recalculate Invoice status
            $totalPaid = $invoice->payments()->sum('amount');
            $newStatus = 'unpaid';
            if ($totalPaid >= $invoice->total_amount) {
                $newStatus = 'paid';
            } elseif ($totalPaid > 0) {
                $newStatus = 'partially_paid';
            }
            $invoice->update(['status' => $newStatus]);
        });
    }
}
