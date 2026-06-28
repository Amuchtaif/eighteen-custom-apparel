<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'office_id',
        'work_shift_id',
        'check_in_time',
        'check_in_latitude',
        'check_in_longitude',
        'check_in_distance',
        'check_in_photo',
        'check_out_time',
        'check_out_latitude',
        'check_out_longitude',
        'check_out_distance',
        'check_out_photo',
        'attendance_status',
        'ip_address',
        'user_agent',
        'device_info',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'check_in_time' => 'datetime',
            'check_out_time' => 'datetime',
            'check_in_latitude' => 'float',
            'check_in_longitude' => 'float',
            'check_out_latitude' => 'float',
            'check_out_longitude' => 'float',
            'check_in_distance' => 'integer',
            'check_out_distance' => 'integer',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function workShift(): BelongsTo
    {
        return $this->belongsTo(WorkShift::class);
    }
}
