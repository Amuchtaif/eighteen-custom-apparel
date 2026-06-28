<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductionProgress;
use App\Models\ProductionProgressHistory;
use Illuminate\Support\Facades\DB;

class ProductionProgressService
{
    /**
     * Initialize progress for a new order.
     */
    public function initializeProgress(Order $order): ProductionProgress
    {
        return ProductionProgress::firstOrCreate(
            ['order_id' => $order->id],
            [
                'print_status' => false,
                'print_at' => null,
                'press_status' => false,
                'press_at' => null,
                'pola_status' => false,
                'pola_at' => null,
                'jahit_status' => false,
                'jahit_at' => null,
                'finish_status' => false,
                'finish_at' => null,
            ]
        );
    }

    /**
     * Toggle a specific stage progress.
     */
    public function toggleStage(int $orderId, string $stage, bool $status, int $userId): array
    {
        return DB::transaction(function () use ($orderId, $stage, $status, $userId) {
            $order = Order::findOrFail($orderId);
            
            // Find or initialize progress
            $progress = $this->initializeProgress($order);

            $stage = strtolower($stage);
            $validStages = ['print', 'press', 'pola', 'jahit', 'finish'];

            if (!in_array($stage, $validStages)) {
                throw new \InvalidArgumentException("Tahap produksi tidak valid: {$stage}");
            }

            $statusField = "{$stage}_status";
            $atField = "{$stage}_at";

            // Update stage progress
            $progress->update([
                $statusField => $status,
                $atField => $status ? now() : null,
            ]);

            // Create progress history log
            ProductionProgressHistory::create([
                'order_id' => $order->id,
                'user_id' => $userId,
                'stage' => ucfirst($stage),
                'status' => $status,
            ]);

            // Re-calculate and update main order status
            $stagesCount = 0;
            $completedCount = 0;

            foreach ($validStages as $s) {
                $stagesCount++;
                if ($progress->{"{$s}_status"}) {
                    $completedCount++;
                }
            }

            $newStatus = 'Menunggu Produksi';
            if ($completedCount === $stagesCount) {
                $newStatus = 'Selesai';
            } elseif ($completedCount > 0) {
                $newStatus = 'Proses Produksi';
            }

            $order->update(['status' => $newStatus]);

            return [
                'success' => true,
                'progress' => $progress,
                'status' => $newStatus,
                'completed_count' => $completedCount,
                'total_count' => $stagesCount,
                'percentage' => round(($completedCount / $stagesCount) * 100),
            ];
        });
    }
}
