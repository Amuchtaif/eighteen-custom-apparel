<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Position;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeRefactorTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->adminUser = User::where('email', 'admin@eighteen.com')->first();
    }

    public function test_admin_can_access_roles_positions_departments_index()
    {
        $response = $this->actingAs($this->adminUser)->get(route('settings.roles.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->adminUser)->get(route('settings.positions.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->adminUser)->get(route('settings.departments.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_crud_roles()
    {
        // Store
        $response = $this->actingAs($this->adminUser)
            ->postJson(route('settings.roles.store'), [
                'name' => 'Designer Magang',
                'description' => 'Intern designer role',
                'is_active' => true,
            ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('roles', ['name' => 'Designer Magang']);

        $roleId = $response->json('data.id');

        // Edit JSON
        $response = $this->actingAs($this->adminUser)
            ->getJson(route('settings.roles.edit', $roleId));
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Designer Magang']);

        // Update
        $response = $this->actingAs($this->adminUser)
            ->putJson(route('settings.roles.update', $roleId), [
                'name' => 'Designer Magang Updated',
                'description' => 'Intern designer role description',
                'is_active' => false,
            ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('roles', ['name' => 'Designer Magang Updated', 'is_active' => false]);

        // Delete
        $response = $this->actingAs($this->adminUser)
            ->deleteJson(route('settings.roles.destroy', $roleId));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('roles', ['id' => $roleId]);
    }

    public function test_admin_cannot_delete_role_in_use()
    {
        // Owner role is in use by Owner user
        $ownerRole = Role::where('name', 'Owner')->first();
        $this->assertNotNull($ownerRole);

        $response = $this->actingAs($this->adminUser)
            ->deleteJson(route('settings.roles.destroy', $ownerRole->id));
        
        $response->assertStatus(422);
        $this->assertDatabaseHas('roles', ['id' => $ownerRole->id]);
    }

    public function test_admin_can_crud_positions()
    {
        // Store
        $response = $this->actingAs($this->adminUser)
            ->postJson(route('settings.positions.store'), [
                'name' => 'Kepala Toko',
                'description' => 'Store Manager',
                'is_active' => true,
            ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('positions', ['name' => 'Kepala Toko']);

        $positionId = $response->json('data.id');

        // Update
        $response = $this->actingAs($this->adminUser)
            ->putJson(route('settings.positions.update', $positionId), [
                'name' => 'Kepala Toko Senior',
                'description' => 'Senior Store Manager',
                'is_active' => true,
            ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('positions', ['name' => 'Kepala Toko Senior']);

        // Delete
        $response = $this->actingAs($this->adminUser)
            ->deleteJson(route('settings.positions.destroy', $positionId));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('positions', ['id' => $positionId]);
    }

    public function test_admin_cannot_delete_position_in_use()
    {
        $employee = Employee::first();
        $this->assertNotNull($employee);
        $this->assertNotNull($employee->position_id);

        $response = $this->actingAs($this->adminUser)
            ->deleteJson(route('settings.positions.destroy', $employee->position_id));

        $response->assertStatus(422);
        $this->assertDatabaseHas('positions', ['id' => $employee->position_id]);
    }

    public function test_employee_code_auto_generation_and_store()
    {
        $role = Role::where('name', 'Sales')->first();
        $position = Position::where('name', 'Sales Representative')->first();
        $department = Department::where('name', 'Sales')->first();
        $office = \App\Models\Office::first() ?? \App\Models\Office::create(['name' => 'HQ', 'address' => 'HQ', 'is_active' => true]);
        $shift = \App\Models\WorkShift::first() ?? \App\Models\WorkShift::create(['name' => 'Morning', 'start_time' => '08:00', 'end_time' => '17:00', 'is_active' => true]);

        // Get dynamic next code
        $nextCode = Employee::generateEmployeeCode();
        $this->assertEquals('EMP-00005', $nextCode); // We have 4 seeded employees in UserSeeder

        $response = $this->actingAs($this->adminUser)
            ->postJson(route('employees.store'), [
                'name' => 'Karyawan Baru Test',
                'email' => 'karyawanbaru@eighteen.com',
                'password' => 'password123',
                'role_id' => $role->id,
                'position_id' => $position->id,
                'department_id' => $department->id,
                'join_date' => '2026-06-24',
                'status' => 'active',
                'office_id' => $office->id,
                'work_shift_id' => $shift->id,
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('employees', [
            'employee_code' => 'EMP-00005',
            'position_id' => $position->id,
            'department_id' => $department->id,
        ]);
    }
}
