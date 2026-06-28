<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Package;
use App\Models\User;
use App\Models\ProductionStage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class ProductionProgressTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $productionUser;
    protected $salesUser;
    protected $customer;
    protected $package;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->adminUser = User::where('email', 'admin@eighteen.com')->first();
        $this->productionUser = User::where('email', 'production@eighteen.com')->first();
        $this->salesUser = User::where('email', 'sales@eighteen.com')->first();
        $this->customer = Customer::first();
        $this->package = Package::first() ?? Package::create([
            'name' => 'Premium Test',
            'description' => 'Test Premium package',
            'is_active' => true,
        ]);
    }

    public function test_order_creation_initializes_production_stage_log()
    {
        $order = Order::create([
            'kode_order' => 'ORD-202606-0001',
            'customer_id' => $this->customer->id,
            'nama_team' => 'Test Team Futsal',
            'bahan' => 'Dryfit',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(10)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 12,
            'qty_tink' => 0,
            'total_tagihan' => 1200000,
            'dp' => 0,
            'status_produksi' => 'Antrian',
            'catatan' => 'Test notes',
        ]);

        $this->assertEquals('Antrian', $order->status_produksi);
    }

    public function test_transition_production_stage()
    {
        $order = Order::create([
            'kode_order' => 'ORD-202606-0002',
            'customer_id' => $this->customer->id,
            'nama_team' => 'Test Team Futsal',
            'bahan' => 'Dryfit',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(5)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 20,
            'qty_tink' => 0,
            'total_tagihan' => 2000000,
            'dp' => 1000000,
            'status_produksi' => 'Antrian',
        ]);

        $printStage = ProductionStage::where('name', 'Print')->first();
        $this->assertNotNull($printStage);

        $response = $this->actingAs($this->productionUser)
            ->post(route('orders.transition-stage', $order->id), [
                'stage_id' => $printStage->id,
                'notes' => 'Telah di-print',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('Print', $order->fresh()->status_produksi);

        $this->assertDatabaseHas('production_logs', [
            'order_id' => $order->id,
            'stage_id' => $printStage->id,
            'notes' => 'Telah di-print',
        ]);
    }

    public function test_order_deadline_helpers()
    {
        Carbon::setTestNow(Carbon::create(2026, 6, 20));

        // 1. Hijau (> 3 Hari)
        $orderAman = new Order(['deadline' => Carbon::create(2026, 6, 30)]); // 10 days remaining
        $this->assertEquals(10, $orderAman->sisa_hari);
        $this->assertEquals('10 Hari Lagi', $orderAman->sisa_hari_text);
        $this->assertEquals('green', $orderAman->deadline_color);

        // 2. Kuning (<= 3 Hari)
        $orderNear = new Order(['deadline' => Carbon::create(2026, 6, 23)]); // 3 days remaining
        $this->assertEquals(3, $orderNear->sisa_hari);
        $this->assertEquals('3 Hari Lagi', $orderNear->sisa_hari_text);
        $this->assertEquals('yellow', $orderNear->deadline_color);

        // 3. Besok (1 Hari)
        $orderCrit = new Order(['deadline' => Carbon::create(2026, 6, 21)]); // 1 day remaining
        $this->assertEquals(1, $orderCrit->sisa_hari);
        $this->assertEquals('Besok', $orderCrit->sisa_hari_text);
        $this->assertEquals('yellow', $orderCrit->deadline_color);

        // 4. Hari Ini (0 Hari)
        $orderToday = new Order(['deadline' => Carbon::create(2026, 6, 20)]); // 0 days remaining
        $this->assertEquals(0, $orderToday->sisa_hari);
        $this->assertEquals('Hari Ini', $orderToday->sisa_hari_text);
        $this->assertEquals('yellow', $orderToday->deadline_color);

        // 5. Terlambat (< 0 Hari)
        $orderLate = new Order(['deadline' => Carbon::create(2026, 6, 18)]); // -2 days remaining
        $this->assertEquals(-2, $orderLate->sisa_hari);
        $this->assertEquals('Terlambat 2 Hari', $orderLate->sisa_hari_text);
        $this->assertEquals('red', $orderLate->deadline_color);

        Carbon::setTestNow(); // Reset test time
    }
}
