<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderAttachment;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomOrderTest extends TestCase
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

    public function test_guest_can_access_custom_order_form()
    {
        $response = $this->get(route('custom-order.form'));

        $response->assertStatus(200);
        $response->assertViewIs('custom-order.form');
    }

    public function test_guest_can_submit_custom_order_with_attachments()
    {
        Storage::fake('public');

        $logoFile = UploadedFile::fake()->create('logo_sample.png', 500);
        $designFile = UploadedFile::fake()->create('design_spec.pdf', 1000);

        $formData = [
            'nama_pemesan' => 'Budi Santoso',
            'no_wa' => '6281234567890',
            'email' => 'budi@example.com',
            'kota_kabupaten' => 'Surabaya',
            'instansi_komunitas' => 'Komunitas Gowes',
            'nama_pesanan' => 'Jersey Gowes Suroboyo',
            'jenis_apparel' => 'Jersey',
            'jumlah_pesanan' => 25,
            'target_penggunaan' => now()->addDays(20)->toDateString(),
            'budget_perkiraan' => 'Rp 150.000',
            'catatan_kebutuhan' => 'Lengan panjang, kerah polo.',
            'logo_files' => [$logoFile],
            'design_files' => [$designFile],
        ];

        $response = $this->post(route('custom-order.submit'), $formData);

        $response->assertRedirect(route('custom-order.success'));
        $response->assertSessionHas('success_order_code');
        $response->assertSessionHas('success_nama_pemesan', 'Budi Santoso');

        // Check if customer was created
        $customer = Customer::where('phone', '6281234567890')->first();
        $this->assertNotNull($customer);
        $this->assertEquals('Komunitas Gowes', $customer->company_name);
        $this->assertEquals('Budi Santoso', $customer->contact_person);

        // Check if order was saved
        $order = Order::where('customer_id', $customer->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals('Jersey Gowes Suroboyo', $order->nama_team);
        $this->assertEquals(25, $order->qty_esti);
        $this->assertEquals(150000.00, $order->budget_perkiraan);
        $this->assertEquals('Landing Page', $order->source);
        $this->assertEquals('Menunggu Review', $order->status_produksi);

        // Check if attachments were saved
        $attachments = OrderAttachment::where('order_id', $order->id)->get();
        $this->assertCount(2, $attachments);

        $logoAttachment = $attachments->where('file_type', 'logo')->first();
        $this->assertNotNull($logoAttachment);
        $this->assertEquals('logo_sample.png', $logoAttachment->file_name);
        Storage::disk('public')->assertExists($logoAttachment->file_path);

        $designAttachment = $attachments->where('file_type', 'design_reference')->first();
        $this->assertNotNull($designAttachment);
        $this->assertEquals('design_spec.pdf', $designAttachment->file_name);
        Storage::disk('public')->assertExists($designAttachment->file_path);
    }

    public function test_guest_cannot_submit_custom_order_with_missing_required_fields()
    {
        $response = $this->post(route('custom-order.submit'), []);

        $response->assertSessionHasErrors([
            'nama_pemesan',
            'no_wa',
            'nama_pesanan',
            'jenis_apparel',
            'jumlah_pesanan',
        ]);
    }

    public function test_success_page_uses_dynamic_whatsapp_number()
    {
        \App\Models\Setting::setValue('whatsapp_number', '628999999999');

        $response = $this->withSession([
            'success_order_code' => 'ORD-202606-0001',
            'success_nama_pemesan' => 'Andi',
        ])->get(route('custom-order.success'));

        $response->assertStatus(200);
        $response->assertSee('https://wa.me/628999999999');
        $response->assertSee('ORD-202606-0001');
    }
}
