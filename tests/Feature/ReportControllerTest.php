<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Payment;
use App\Models\Employee;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $customerUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->adminUser = User::where('email', 'admin@eighteen.com')->first();
        $this->customerUser = User::where('email', 'customer@eighteen.com')->first();
    }

    public function test_admin_can_access_reports_dashboard()
    {
        // Record a mock payment to verify calculation doesn't throw errors
        $customer = Customer::first();
        $package = \App\Models\Package::first() ?? \App\Models\Package::create([
            'name' => 'Premium Test',
            'description' => 'Test Premium package',
            'is_active' => true,
        ]);
        $order = \App\Models\Order::create([
            'kode_order' => 'ORD-REP-1',
            'customer_id' => $customer->id,
            'nama_team' => 'Test Team',
            'bahan' => 'Dryfit',
            'tanggal_masuk' => now()->toDateString(),
            'deadline' => now()->addDays(10)->toDateString(),
            'package_id' => $package->id,
            'qty_esti' => 10,
            'qty_tink' => 0,
            'total_tagihan' => 100000,
            'dp' => 0,
            'status_produksi' => 'Antrian',
        ]);
        $invoice = \App\Models\Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => 'INV-REP-1',
            'invoice_date' => now(),
            'due_date' => now()->addDays(10),
            'total_amount' => 100000,
            'status' => 'unpaid',
        ]);
        Payment::create([
            'invoice_id' => $invoice->id,
            'payment_date' => now()->toDateString(),
            'amount' => 100000,
            'payment_method' => 'cash',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertViewIs('reports.index');
        $response->assertViewHasAll(['salesLabels', 'salesValues', 'attendanceRecap']);
    }

    public function test_customer_cannot_access_admin_reports_dashboard()
    {
        $response = $this->actingAs($this->customerUser)
            ->get(route('reports.index'));

        // Customer does not have the administrative roles required, should abort with 403
        $response->assertStatus(403);
    }

    public function test_admin_can_update_employee_details_and_user_name()
    {
        $employee = Employee::first();
        $this->assertNotNull($employee);

        $position = \App\Models\Position::first();
        $department = \App\Models\Department::first();

        $originalCode = $employee->employee_code;

        $response = $this->actingAs($this->adminUser)
            ->put(route('employees.update', $employee->id), [
                'name' => 'Updated Employee Name',
                'email' => $employee->user->email,
                'role_id' => $employee->user->role_id,
                'office_id' => $employee->office_id,
                'work_shift_id' => $employee->work_shift_id,
                'user_id' => $employee->user_id,
                'position_id' => $position->id,
                'department_id' => $department->id,
                'join_date' => '2026-06-01',
                'status' => 'active',
            ]);

        $response->assertRedirect(route('employees.index'));
        $this->assertEquals('Updated Employee Name', $employee->user->fresh()->name);
        $this->assertEquals($originalCode, $employee->fresh()->employee_code);
    }
}
