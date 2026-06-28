<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sequence',
    ];

    public function productionLogs(): HasMany
    {
        return $this->hasMany(ProductionLog::class, 'stage_id');
    }
}
