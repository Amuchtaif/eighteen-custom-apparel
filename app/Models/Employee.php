<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_code',
        'role_id',
        'position_id',
        'department_id',
        'join_date',
        'status',
        'office_id',
        'work_shift_id',
    ];

    protected function casts(): array
    {
        return [
            'join_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function workShift(): BelongsTo
    {
        return $this->belongsTo(WorkShift::class);
    }

    /**
     * Generate a unique sequential employee code (EMP-XXXXX) securely.
     */
    public static function generateEmployeeCode(): string
    {
        return DB::transaction(function () {
            // Find max numeric value from code
            $lastCode = self::withTrashed()
                ->where('employee_code', 'LIKE', 'EMP-%')
                ->lockForUpdate()
                ->get()
                ->map(function ($emp) {
                    if (preg_match('/EMP-(\d+)/', $emp->employee_code, $matches)) {
                        return (int) $matches[1];
                    }
                    return 0;
                })
                ->max();

            $nextNumber = ($lastCode ?? 0) + 1;
            return 'EMP-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        });
    }
}
