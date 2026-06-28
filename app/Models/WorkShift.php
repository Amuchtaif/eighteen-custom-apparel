<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'late_tolerance_minutes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'late_tolerance_minutes' => 'integer',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
