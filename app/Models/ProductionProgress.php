<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionProgress extends Model
{
    use HasFactory;

    protected $table = 'production_progress';

    protected $fillable = [
        'order_id',
        'print_status',
        'print_at',
        'press_status',
        'press_at',
        'pola_status',
        'pola_at',
        'jahit_status',
        'jahit_at',
        'finish_status',
        'finish_at',
    ];

    protected function casts(): array
    {
        return [
            'print_status' => 'boolean',
            'print_at' => 'datetime',
            'press_status' => 'boolean',
            'press_at' => 'datetime',
            'pola_status' => 'boolean',
            'pola_at' => 'datetime',
            'jahit_status' => 'boolean',
            'jahit_at' => 'datetime',
            'finish_status' => 'boolean',
            'finish_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
