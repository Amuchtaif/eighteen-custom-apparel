<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Package;
use App\Models\ProductionStage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $customer;
    protected $package;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->adminUser = User::where('email', 'admin@eighteen.com')->first();
        $this->customer = Customer::first();
        
        $this->package = Package::first() ?? Package::create([
            'name' => 'Premium Test',
            'description' => 'Test Premium package',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_access_orders_index()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('orders.index'));

        $response->assertStatus(200);
        $response->assertViewIs('orders.index');
    }

    public function test_admin_can_access_orders_create()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('orders.create'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_order()
    {
        $postData = [
            'customer_id' => $this->customer->id,
            'nama_team' => 'FC Barcelona',
            'bahan' => 'Dryfit Jarum',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(10)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 10,
            'qty_tink' => 0,
            'total_tagihan' => 1200000,
            'dp' => 500000,
            'catatan' => 'Some direct order notes',
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('orders.store'), $postData);

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'customer_id' => $this->customer->id,
            'nama_team' => 'FC Barcelona',
            'total_tagihan' => 1200000,
            'dp' => 500000,
            'sisa_tagihan' => 700000,
            'status_pembayaran' => 'DP',
        ]);
    }

    public function test_admin_can_access_orders_show()
    {
        $order = Order::create([
            'kode_order' => 'ORD-12345',
            'customer_id' => $this->customer->id,
            'nama_team' => 'Test Team',
            'bahan' => 'Dryfit',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(14)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 10,
            'qty_tink' => 0,
            'total_tagihan' => 120000,
            'dp' => 0,
            'status_produksi' => 'Antrian',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('orders.show', $order->id));

        $response->assertStatus(200);
        $response->assertViewIs('orders.show');
    }

    public function test_admin_can_access_orders_edit()
    {
        $order = Order::create([
            'kode_order' => 'ORD-12345',
            'customer_id' => $this->customer->id,
            'nama_team' => 'Test Team',
            'bahan' => 'Dryfit',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(14)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 10,
            'qty_tink' => 0,
            'total_tagihan' => 120000,
            'dp' => 0,
            'status_produksi' => 'Antrian',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('orders.edit', $order->id));

        $response->assertStatus(200);
    }

    public function test_admin_can_update_order()
    {
        $order = Order::create([
            'kode_order' => 'ORD-12345',
            'customer_id' => $this->customer->id,
            'nama_team' => 'Test Team',
            'bahan' => 'Dryfit',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(14)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 10,
            'qty_tink' => 0,
            'total_tagihan' => 120000,
            'dp' => 0,
            'status_produksi' => 'Antrian',
        ]);

        $updateData = [
            'customer_id' => $this->customer->id,
            'nama_team' => 'Updated Team',
            'bahan' => 'Dryfit Jarum',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(12)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 12,
            'total_tagihan' => 150000,
            'dp' => 50000,
            'status_produksi' => 'Print',
            'catatan' => 'Updated order details',
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('orders.update', $order->id), $updateData);

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'nama_team' => 'Updated Team',
            'qty_esti' => 12,
            'total_tagihan' => 150000,
            'dp' => 50000,
            'sisa_tagihan' => 100000,
            'status_pembayaran' => 'DP',
            'status_produksi' => 'Print',
        ]);
    }

    public function test_admin_can_update_players()
    {
        $order = Order::create([
            'kode_order' => 'ORD-12345',
            'customer_id' => $this->customer->id,
            'nama_team' => 'Test Team',
            'bahan' => 'Dryfit',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(14)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 2,
            'qty_tink' => 0,
            'total_tagihan' => 120000,
            'dp' => 0,
            'status_produksi' => 'Antrian',
        ]);

        $playersData = [
            'players' => [
                [
                    'player_name' => 'RONALDO',
                    'player_number' => '7',
                    'size' => 'L',
                    'notes' => 'Captain arm band',
                ],
                [
                    'player_name' => 'MESSI',
                    'player_number' => '10',
                    'size' => 'M',
                    'notes' => 'Short sleeves',
                ]
            ]
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('orders.update-players', $order->id), $playersData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('order_players', [
            'order_id' => $order->id,
            'player_name' => 'RONALDO',
            'player_number' => '7',
            'size' => 'L',
        ]);

        $this->assertDatabaseHas('order_players', [
            'order_id' => $order->id,
            'player_name' => 'MESSI',
            'player_number' => '10',
            'size' => 'M',
        ]);
    }

    public function test_admin_can_upload_design()
    {
        Storage::fake('public');

        $order = Order::create([
            'kode_order' => 'ORD-12345',
            'customer_id' => $this->customer->id,
            'nama_team' => 'Test Team',
            'bahan' => 'Dryfit',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(14)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 10,
            'qty_tink' => 0,
            'total_tagihan' => 120000,
            'dp' => 0,
            'status_produksi' => 'Antrian',
        ]);

        $file = UploadedFile::fake()->create('mockup.png', 500);

        $response = $this->actingAs($this->adminUser)
            ->post(route('orders.upload-design', $order->id), [
                'design_file' => $file,
                'file_name' => 'Front Mockup Draft',
                'notes' => 'First digital draft draft comments',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $latestDesign = $order->designFiles()->first();
        $this->assertNotNull($latestDesign);
        $this->assertEquals('Front Mockup Draft', $latestDesign->file_name);
        $this->assertEquals('v1', $latestDesign->version);
        Storage::disk('public')->assertExists($latestDesign->file_path);
    }

    public function test_admin_can_transition_stage()
    {
        $order = Order::create([
            'kode_order' => 'ORD-12345',
            'customer_id' => $this->customer->id,
            'nama_team' => 'Test Team',
            'bahan' => 'Dryfit',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(14)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 10,
            'qty_tink' => 0,
            'total_tagihan' => 120000,
            'dp' => 0,
            'status_produksi' => 'Antrian',
        ]);

        $printStage = ProductionStage::where('name', 'Print')->first();

        $response = $this->actingAs($this->adminUser)
            ->post(route('orders.transition-stage', $order->id), [
                'stage_id' => $printStage->id,
                'notes' => 'Transitioned to Print stage',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('production_logs', [
            'order_id' => $order->id,
            'stage_id' => $printStage->id,
            'notes' => 'Transitioned to Print stage',
        ]);

        $this->assertEquals('Print', $order->fresh()->status_produksi);
    }

    public function test_admin_can_delete_order()
    {
        $order = Order::create([
            'kode_order' => 'ORD-12345',
            'customer_id' => $this->customer->id,
            'nama_team' => 'Test Team',
            'bahan' => 'Dryfit',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(14)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 10,
            'qty_tink' => 0,
            'total_tagihan' => 120000,
            'dp' => 0,
            'status_produksi' => 'Antrian',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('orders.destroy', $order->id));

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('orders', [
            'id' => $order->id,
        ]);
    }

    public function test_public_user_can_track_existing_order()
    {
        $order = Order::create([
            'kode_order' => 'ORD-99999',
            'customer_id' => $this->customer->id,
            'nama_team' => 'Test Team',
            'bahan' => 'Dryfit',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(14)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 10,
            'qty_tink' => 0,
            'total_tagihan' => 120000,
            'dp' => 0,
            'status_produksi' => 'Antrian',
        ]);

        $response = $this->get(route('orders.track-public', ['code' => 'ORD-99999']));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'order' => [
                'order_number' => 'ORD-99999',
                'status' => 'Antrian',
            ]
        ]);
        $response->assertJsonStructure([
            'success',
            'order' => ['order_number', 'status', 'date', 'deadline'],
            'stages' => [
                '*' => ['name', 'status', 'description']
            ]
        ]);
    }

    public function test_public_user_receives_error_for_non_existent_order()
    {
        $response = $this->get(route('orders.track-public', ['code' => 'ORD-DOES-NOT-EXIST']));

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Pesanan tidak ditemukan. Silakan periksa nomor pesanan dan coba lagi.'
        ]);
    }

    public function test_public_user_receives_error_for_missing_code_parameter()
    {
        $response = $this->get(route('orders.track-public'));

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Silakan masukkan nomor pesanan.'
        ]);
    }
}
