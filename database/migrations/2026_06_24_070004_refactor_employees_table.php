<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add nullable foreign key columns to employees
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('user_id')->constrained('roles')->nullOnDelete();
            $table->foreignId('position_id')->nullable()->after('role_id')->constrained('positions')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->after('position_id')->constrained('departments')->nullOnDelete();
        });

        // 2. Ensure initial positions and departments are inserted so we can map to them
        $departments = [
            ['name' => 'Sales', 'description' => 'Sales department', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Produksi', 'description' => 'Production department', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Marketing', 'description' => 'Marketing department', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keuangan', 'description' => 'Finance department', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'HRD', 'description' => 'Human Resources department', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($departments as $dept) {
            if (!DB::table('departments')->where('name', $dept['name'])->exists()) {
                DB::table('departments')->insert($dept);
            }
        }

        $positions = [
            ['name' => 'Sales Representative', 'description' => 'Sales Representative', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sales Manager', 'description' => 'Sales Manager', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Operator Produksi', 'description' => 'Production Operator', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Supervisor Produksi', 'description' => 'Production Supervisor', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Admin Keuangan', 'description' => 'Finance Administrator', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Staff HRD', 'description' => 'HRD Staff member', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($positions as $pos) {
            if (!DB::table('positions')->where('name', $pos['name'])->exists()) {
                DB::table('positions')->insert($pos);
            }
        }

        // 3. Migrate existing employees data
        $employees = DB::table('employees')->get();
        foreach ($employees as $employee) {
            $userId = $employee->user_id;
            
            // Get user's role_id
            $user = DB::table('users')->where('id', $userId)->first();
            $roleId = $user ? $user->role_id : null;

            // Map position
            $positionId = null;
            if ($employee->position) {
                // Try case-insensitive exact match
                $posRecord = DB::table('positions')
                    ->whereRaw('LOWER(name) = ?', [strtolower(trim($employee->position))])
                    ->first();
                
                if ($posRecord) {
                    $positionId = $posRecord->id;
                } else {
                    // Create a new position dynamically if it doesn't exist
                    $positionId = DB::table('positions')->insertGetId([
                        'name' => trim($employee->position),
                        'description' => 'Auto-migrated from employee position text',
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Map department
            $departmentId = null;
            if ($employee->department) {
                // Normalise names (e.g. Human Resources -> HRD, Production -> Produksi, Finance -> Keuangan)
                $deptName = trim($employee->department);
                $normalizedName = $deptName;
                $lowerDept = strtolower($deptName);
                if ($lowerDept === 'human resources') {
                    $normalizedName = 'HRD';
                } elseif ($lowerDept === 'production') {
                    $normalizedName = 'Produksi';
                } elseif ($lowerDept === 'finance') {
                    $normalizedName = 'Keuangan';
                }

                $deptRecord = DB::table('departments')
                    ->whereRaw('LOWER(name) = ?', [strtolower($normalizedName)])
                    ->first();

                if ($deptRecord) {
                    $departmentId = $deptRecord->id;
                } else {
                    // Create a new department dynamically
                    $departmentId = DB::table('departments')->insertGetId([
                        'name' => $deptName,
                        'description' => 'Auto-migrated from employee department text',
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Update employee record
            DB::table('employees')
                ->where('id', $employee->id)
                ->update([
                    'role_id' => $roleId,
                    'position_id' => $positionId,
                    'department_id' => $departmentId,
                ]);
        }

        // 4. Drop the old position and department columns
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['position', 'department']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back position and department columns
        Schema::table('employees', function (Blueprint $table) {
            $table->string('position')->nullable()->after('employee_code');
            $table->string('department')->nullable()->after('position');
        });

        // Restore old data if possible
        $employees = DB::table('employees')->get();
        foreach ($employees as $employee) {
            $posName = null;
            if ($employee->position_id) {
                $pos = DB::table('positions')->where('id', $employee->position_id)->first();
                $posName = $pos ? $pos->name : null;
            }
            $deptName = null;
            if ($employee->department_id) {
                $dept = DB::table('departments')->where('id', $employee->department_id)->first();
                $deptName = $dept ? $dept->name : null;
            }

            DB::table('employees')
                ->where('id', $employee->id)
                ->update([
                    'position' => $posName,
                    'department' => $deptName,
                ]);
        }

        // Drop foreign keys
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['position_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn(['role_id', 'position_id', 'department_id']);
        });
    }
};
