<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_order',
        'customer_id',
        'nama_team',
        'bahan',
        'tanggal_masuk',
        'deadline',
        'package_id',
        'qty_esti',
        'qty_tink',
        'total_tagihan',
        'dp',
        'sisa_tagihan',
        'status_pembayaran',
        'status_produksi',
        'catatan',
        'source',
        'jenis_apparel',
        'target_penggunaan',
        'budget_perkiraan',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'deadline' => 'date',
        'target_penggunaan' => 'date',
        'total_tagihan' => 'float',
        'dp' => 'float',
        'sisa_tagihan' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($order) {
            $order->sisa_tagihan = max(0.00, $order->total_tagihan - $order->dp);
            if ($order->dp <= 0) {
                $order->status_pembayaran = 'Belum DP';
            } elseif ($order->sisa_tagihan <= 0) {
                $order->status_pembayaran = 'Lunas';
            } else {
                $order->status_pembayaran = 'DP';
            }
        });
    }

    // Dynamic Attribute Aliasing for backward compatibility with external code/tests
    protected function orderNumber(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['kode_order'] ?? null,
            set: fn ($value) => [
                'kode_order' => $value,
            ]
        );
    }

    protected function orderDate(): Attribute
    {
        return Attribute::make(
            get: fn () => isset($this->attributes['tanggal_masuk']) ? Carbon::parse($this->attributes['tanggal_masuk']) : null,
            set: fn ($value) => [
                'tanggal_masuk' => $value,
            ]
        );
    }

    protected function notes(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['catatan'] ?? null,
            set: fn ($value) => [
                'catatan' => $value,
            ]
        );
    }

    protected function grandTotal(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['total_tagihan'] ?? null,
            set: fn ($value) => [
                'total_tagihan' => $value,
            ]
        );
    }
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['status_produksi'] ?? 'Antrian',
            set: fn ($value) => [
                'status_produksi' => $value === 'completed' ? 'Selesai' : ($value === 'ready_to_ship' ? 'Siap Kirim' : ($value === 'in_production' ? 'Jahit' : 'Antrian')),
            ]
        );
    }

    // Calculated Helpers
    public function getSisaHariAttribute(): ?int
    {
        if (!$this->deadline) {
            return null;
        }
        $today = Carbon::today();
        $deadline = Carbon::parse($this->deadline)->startOfDay();
        return $today->diffInDays($deadline, false);
    }

    public function getSisaHariTextAttribute(): string
    {
        $sisa = $this->sisa_hari;
        if ($sisa === null) {
            return '-';
        }
        if ($sisa < 0) {
            return 'Terlambat ' . abs($sisa) . ' Hari';
        }
        if ($sisa === 0) {
            return 'Hari Ini';
        }
        if ($sisa === 1) {
            return 'Besok';
        }
        return $sisa . ' Hari Lagi';
    }

    public function getDeadlineColorAttribute(): string
    {
        $sisa = $this->sisa_hari;
        if ($sisa === null) {
            return 'green';
        }
        if ($sisa < 0) {
            return 'red';
        }
        if ($sisa <= 3) {
            return 'yellow';
        }
        return 'green';
    }

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function players(): HasMany
    {
        return $this->hasMany(OrderPlayer::class, 'order_id');
    }

    public function designFiles(): HasMany
    {
        return $this->hasMany(DesignFile::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function productionLogs(): HasMany
    {
        return $this->hasMany(ProductionLog::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(OrderAttachment::class);
    }
}
