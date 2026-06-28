<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\User;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FinanceAndPortalControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $customerUser;
    protected $customer;
    protected $package;
    protected $order;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->adminUser = User::where('email', 'admin@eighteen.com')->first();
        $this->customerUser = User::where('email', 'customer@eighteen.com')->first();
        $this->customer = Customer::where('email', 'customer@eighteen.com')->first();
        
        $this->package = Package::first() ?? Package::create([
            'name' => 'Premium Test',
            'description' => 'Test Premium package',
            'is_active' => true,
        ]);

        $this->order = Order::create([
            'kode_order' => 'ORD-FIN-999',
            'customer_id' => $this->customer->id,
            'nama_team' => 'Test Team',
            'bahan' => 'Dryfit Jarum',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(14)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 5,
            'qty_tink' => 0,
            'total_tagihan' => 500000,
            'dp' => 0,
            'status_produksi' => 'Antrian',
        ]);
    }

    public function test_admin_can_access_invoices_index()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('invoices.index'));

        $response->assertStatus(200);
        $response->assertViewIs('invoices.index');
    }

    public function test_admin_can_access_invoices_create()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('invoices.create'));

        $response->assertStatus(200);
        $response->assertViewIs('invoices.create');
    }

    public function test_admin_can_generate_invoice()
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('invoices.store'), [
                'order_id' => $this->order->id,
            ]);

        $response->assertRedirect(route('invoices.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('invoices', [
            'order_id' => $this->order->id,
            'total_amount' => 500000,
            'status' => 'unpaid',
        ]);
    }

    public function test_admin_can_view_invoice_details()
    {
        $invoice = Invoice::create([
            'order_id' => $this->order->id,
            'invoice_number' => 'INV-TEST-01',
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'total_amount' => 500000,
            'status' => 'unpaid',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('invoices.show', $invoice->id));

        $response->assertStatus(200);
        $response->assertViewIs('invoices.show');
    }

    public function test_admin_can_delete_unpaid_invoice()
    {
        $invoice = Invoice::create([
            'order_id' => $this->order->id,
            'invoice_number' => 'INV-TEST-01',
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'total_amount' => 500000,
            'status' => 'unpaid',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('invoices.destroy', $invoice->id));

        $response->assertRedirect(route('invoices.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('invoices', [
            'id' => $invoice->id,
        ]);
    }

    public function test_admin_can_access_payments_index()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('payments.index'));

        $response->assertStatus(200);
        $response->assertViewIs('payments.index');
    }

    public function test_admin_can_access_payments_create()
    {
        $invoice = Invoice::create([
            'order_id' => $this->order->id,
            'invoice_number' => 'INV-TEST-01',
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'total_amount' => 500000,
            'status' => 'unpaid',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('payments.create', ['invoice_id' => $invoice->id]));

        $response->assertStatus(200);
        $response->assertViewIs('payments.create');
    }

    public function test_admin_can_manually_record_payment()
    {
        $invoice = Invoice::create([
            'order_id' => $this->order->id,
            'invoice_number' => 'INV-TEST-01',
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'total_amount' => 500000,
            'status' => 'unpaid',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('payments.store'), [
                'invoice_id' => $invoice->id,
                'payment_date' => now()->toDateString(),
                'amount' => 500000,
                'payment_method' => 'cash',
                'reference_number' => 'CASH-REC-1',
                'notes' => 'Received cash at shop',
            ]);

        $response->assertRedirect(route('payments.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'amount' => 500000,
            'payment_method' => 'cash',
            'reference_number' => 'CASH-REC-1',
        ]);

        $this->assertEquals('paid', $invoice->fresh()->status);
    }

    public function test_customer_can_access_portal_dashboard()
    {
        $response = $this->actingAs($this->customerUser)
            ->get(route('portal.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('portal.dashboard');
        $response->assertViewHas('orders');
    }

    public function test_customer_without_customer_profile_is_logged_out()
    {
        $noProfileUser = User::create([
            'email' => 'noprofile@example.com',
            'name' => 'No Profile',
            'phone' => '123',
            'password' => bcrypt('password'),
            'role_id' => $this->customerUser->role_id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($noProfileUser)
            ->get(route('portal.dashboard'));

        $response->assertRedirect(route('portal.login'));
        $this->assertFalse(auth()->check());
    }

    public function test_customer_can_select_order_details_in_portal()
    {
        $response = $this->actingAs($this->customerUser)
            ->get(route('portal.dashboard', ['order_id' => $this->order->id]));

        $response->assertStatus(200);
        $response->assertViewHas('selectedOrder');
        $this->assertEquals($this->order->id, $response->viewData('selectedOrder')->id);
    }

    public function test_customer_can_upload_payment_proof()
    {
        Storage::fake('public');

        $invoice = Invoice::create([
            'order_id' => $this->order->id,
            'invoice_number' => 'INV-TEST-01',
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'total_amount' => 500000,
            'status' => 'unpaid',
        ]);

        $file = UploadedFile::fake()->image('receipt.jpg');

        $response = $this->actingAs($this->customerUser)
            ->post(route('portal.upload-proof', $invoice->id), [
                'amount' => 250000,
                'payment_method' => 'bank_transfer',
                'reference_number' => 'TXN-PORTAL-1',
                'proof_file' => $file,
                'notes' => 'Downpayment proof',
            ]);

        $response->assertRedirect(route('portal.dashboard', ['order_id' => $this->order->id]));
        $response->assertSessionHas('success');

        $payment = Payment::where('reference_number', 'TXN-PORTAL-1')->first();
        $this->assertNotNull($payment);
        $this->assertEquals(250000, $payment->amount);
        $this->assertEquals('partially_paid', $invoice->fresh()->status);

        Storage::disk('public')->assertExists($payment->proof_file);
    }

    public function test_customer_cannot_upload_proof_to_another_customers_invoice()
    {
        $otherCustomer = Customer::create([
            'company_name' => 'Other Corp',
            'contact_person' => 'Other Client',
            'phone' => '456',
            'email' => 'otherclient@example.com',
            'address' => 'Addr',
            'city' => 'City',
        ]);

        $otherOrder = Order::create([
            'kode_order' => 'ORD-OTHER-1',
            'customer_id' => $otherCustomer->id,
            'nama_team' => 'Other Team',
            'bahan' => 'Dryfit',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(14)->toDateString(),
            'package_id' => $this->package->id,
            'qty_esti' => 5,
            'qty_tink' => 0,
            'total_tagihan' => 200000,
            'dp' => 0,
            'status_produksi' => 'Antrian',
        ]);

        $invoice = Invoice::create([
            'order_id' => $otherOrder->id,
            'invoice_number' => 'INV-OTHER-01',
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'total_amount' => 200000,
            'status' => 'unpaid',
        ]);

        $file = UploadedFile::fake()->image('receipt.jpg');

        $response = $this->actingAs($this->customerUser)
            ->post(route('portal.upload-proof', $invoice->id), [
                'amount' => 200000,
                'payment_method' => 'bank_transfer',
                'reference_number' => 'TXN-PORTAL-BAD',
                'proof_file' => $file,
            ]);

        $response->assertStatus(403);
    }
}
