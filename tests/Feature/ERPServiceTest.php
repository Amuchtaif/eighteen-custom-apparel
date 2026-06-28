<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\ProductionStage;
use App\Services\OrderService;
use App\Services\ProductionService;
use App\Services\AttendanceService;
use App\Services\PaymentService;

class ERPServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $package;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->package = Package::first() ?? Package::create([
            'name' => 'Premium Test',
            'description' => 'Test Premium package',
            'is_active' => true,
        ]);
    }

    public function test_production_stage_transition()
    {
        $customer = Customer::first();
        $orderService = app(OrderService::class);

        $order = $orderService->createOrder([
            'customer_id' => $customer->id,
            'nama_team' => 'Barcelona Tech',
            'bahan' => 'Dryfit',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(10)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 10,
            'qty_tink' => 0,
            'total_tagihan' => 1000000,
            'dp' => 0,
            'status_produksi' => 'Antrian',
        ]);

        $productionService = app(ProductionService::class);
        
        // Transition to Print Stage
        $printStage = ProductionStage::where('name', 'Print')->first();
        $log = $productionService->transitionStage($order->id, $printStage->id, 'Started printing fabric');

        $this->assertEquals('Print', $order->fresh()->status_produksi);
        $this->assertDatabaseHas('production_logs', [
            'order_id' => $order->id,
            'stage_id' => $printStage->id,
            'notes' => 'Started printing fabric',
        ]);
    }

    public function test_employee_attendance_check_in_out()
    {
        $employee = Employee::first();
        $attendanceService = app(AttendanceService::class);

        // Check in
        $attendance = $attendanceService->checkIn($employee->id, -6.2, 106.8, 'selfies/test.jpg', 'Arrived on time');

        $this->assertDatabaseHas('attendances', [
            'employee_id' => $employee->id,
            'notes' => 'Arrived on time',
        ]);

        // Check out
        $attendanceService->checkOut($employee->id, -6.2, 106.8, 'End of shift');

        $this->assertNotNull($attendance->fresh()->check_out_time);
    }

    public function test_invoice_creation_and_payments()
    {
        $customer = Customer::first();
        $orderService = app(OrderService::class);

        $order = $orderService->createOrder([
            'customer_id' => $customer->id,
            'nama_team' => 'Madrid Tech',
            'bahan' => 'Dryfit Premium',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(10)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 10,
            'qty_tink' => 0,
            'total_tagihan' => 1000000,
            'dp' => 0,
            'status_produksi' => 'Antrian',
        ]);

        $paymentService = app(PaymentService::class);

        // 1. Create Invoice
        $invoice = $paymentService->createInvoiceForOrder($order->id);

        $this->assertDatabaseHas('invoices', [
            'order_id' => $order->id,
            'total_amount' => 1000000,
            'status' => 'unpaid',
        ]);

        // 2. Record Down Payment
        $paymentService->recordPayment($invoice->id, [
            'amount' => 500000,
            'payment_method' => 'bank_transfer',
            'reference_number' => 'REF-DP-123',
        ]);

        $this->assertEquals('partially_paid', $invoice->fresh()->status);

        // 3. Record Full Payment
        $paymentService->recordPayment($invoice->id, [
            'amount' => 500000,
            'payment_method' => 'bank_transfer',
            'reference_number' => 'REF-FP-123',
        ]);

        $this->assertEquals('paid', $invoice->fresh()->status);
    }
}
