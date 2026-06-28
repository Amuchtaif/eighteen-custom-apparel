<?php

namespace App\Services;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomerHistory;
use App\Models\ProductionStage;
use App\Models\ProductionLog;
use Illuminate\Support\Facades\DB;

class QuotationService
{
    /**
     * Generate quotation number format: QUO-YYYYMM-00001
     */
    public function generateQuotationNumber(): string
    {
        $prefix = 'QUO-' . now()->format('Ym') . '-';
        $count = Quotation::where('quotation_number', 'like', $prefix . '%')->count();
        return $prefix . str_pad($count + 1, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new quotation
     */
    public function createQuotation(array $data, array $items): Quotation
    {
        return DB::transaction(function () use ($data, $items) {
            $data['quotation_number'] = $this->generateQuotationNumber();
            $data['status'] = $data['status'] ?? 'draft';

            // Calculate totals
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['qty'] * $item['unit_price'];
            }
            $data['subtotal'] = $subtotal;
            $data['grand_total'] = $subtotal - ($data['discount'] ?? 0);

            $quotation = Quotation::create($data);

            foreach ($items as $item) {
                $quotation->items()->create([
                    'product_id' => $item['product_id'],
                    'description' => $item['description'] ?? null,
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['qty'] * $item['unit_price'],
                ]);
            }

            // Log customer history
            CustomerHistory::create([
                'customer_id' => $quotation->customer_id,
                'user_id' => auth()->id() ?? UserSeederHelper::getFirstAdminId(),
                'activity' => 'Quotation Created',
                'notes' => "Quotation number: {$quotation->quotation_number}",
            ]);

            return $quotation;
        });
    }

    /**
     * Convert quotation to order
     */
    public function convertToOrder(int $quotationId, string $deadline, ?int $userId = null): Order
    {
        return DB::transaction(function () use ($quotationId, $deadline, $userId) {
            $quotation = Quotation::findOrFail($quotationId);
            
            // Generate order number
            $orderPrefix = 'ORD-' . now()->format('Ym') . '-';
            $orderCount = Order::where('kode_order', 'like', $orderPrefix . '%')->count();
            $orderNumber = $orderPrefix . str_pad($orderCount + 1, 5, '0', STR_PAD_LEFT);

            // Create Order
            $order = Order::create([
                'kode_order' => $orderNumber,
                'customer_id' => $quotation->customer_id,
                'quotation_id' => $quotation->id,
                'tanggal_masuk' => now()->toDateString(),
                'deadline' => $deadline,
                'subtotal' => $quotation->subtotal,
                'discount' => $quotation->discount,
                'grand_total' => $quotation->grand_total,
                'status' => 'draft',
                'catatan' => "Converted from Quotation {$quotation->quotation_number}. " . $quotation->notes,
            ]);

            // Initialize Production Progress record
            $order->productionProgress()->create([
                'print_status' => false,
                'press_status' => false,
                'pola_status' => false,
                'jahit_status' => false,
                'finish_status' => false,
            ]);

            // Copy items
            foreach ($quotation->items as $qItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $qItem->product_id,
                    'product_name' => $qItem->product?->name ?? 'Custom Product',
                    'qty' => $qItem->qty,
                    'unit_price' => $qItem->unit_price,
                    'subtotal' => $qItem->subtotal,
                    'notes' => $qItem->description,
                ]);
            }

            // Update Quotation Status
            $quotation->update(['status' => 'converted_to_order']);

            $creatorId = $userId ?? auth()->id() ?? UserSeederHelper::getFirstAdminId();

            // Set Initial Production Stage (Draft)
            $draftStage = ProductionStage::where('name', 'Draft')->first();
            if ($draftStage) {
                ProductionLog::create([
                    'order_id' => $order->id,
                    'stage_id' => $draftStage->id,
                    'updated_by' => $creatorId,
                    'notes' => 'Initial order draft created from quotation conversion.',
                    'started_at' => now(),
                ]);
            }

            // Log customer history
            CustomerHistory::create([
                'customer_id' => $order->customer_id,
                'user_id' => $creatorId,
                'activity' => 'Order Created from Quotation',
                'notes' => "Order number: {$order->kode_order} from Quotation: {$quotation->quotation_number}",
            ]);

            return $order;
        });
    }
}
