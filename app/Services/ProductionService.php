<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductionStage;
use App\Models\ProductionLog;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class ProductionService
{
    /**
     * Transition Order to a new production stage
     */
    public function transitionStage(int $orderId, int $stageId, ?string $notes = null, ?int $userId = null): ProductionLog
    {
        return DB::transaction(function () use ($orderId, $stageId, $notes, $userId) {
            $order = Order::findOrFail($orderId);
            $stage = ProductionStage::findOrFail($stageId);
            $updaterId = $userId ?? auth()->id() ?? UserSeederHelper::getFirstAdminId();

            // Complete any existing active production log for this order
            ProductionLog::where('order_id', $orderId)
                ->whereNull('completed_at')
                ->update(['completed_at' => now()]);

            // Create new production log
            $newLog = ProductionLog::create([
                'order_id' => $orderId,
                'stage_id' => $stageId,
                'updated_by' => $updaterId,
                'notes' => $notes,
                'started_at' => now(),
            ]);

            // Update the order's status_produksi to the new stage's name
            $order->update(['status_produksi' => $stage->name]);

            // Write to Activity Logs
            ActivityLog::create([
                'user_id' => $updaterId,
                'module' => 'Production',
                'action' => 'Stage Changed',
                'description' => "Order {$order->kode_order} transitioned to stage '{$stage->name}'.",
                'ip_address' => request()->ip(),
            ]);

            return $newLog;
        });
    }
}
