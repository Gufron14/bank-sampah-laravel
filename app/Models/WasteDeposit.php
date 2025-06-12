<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteDeposit extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = ['user_id', 'waste_items', 'total_weight', 'total_amount', 'status', 'pickup_date', 'notes'];

    protected $casts = [
        'waste_items' => 'array',
        'total_weight' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'pickup_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionHistory()
    {
        return $this->morphOne(TransactionHistory::class, 'reference');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($wasteDeposit) {
            // Buat transaction history saat waste deposit dibuat
            TransactionHistory::create([
                'user_id' => $wasteDeposit->user_id,
                'type' => TransactionHistory::TYPE_DEPOSIT,
                'amount' => $wasteDeposit->total_amount,
                'description' => 'Setor Sampah - ' . $wasteDeposit->formatted_total_weight . 'Kg',
                'reference_type' => WasteDeposit::class,
                'reference_id' => $wasteDeposit->id,
            ]);
        });

        static::updated(function ($wasteDeposit) {
            // Update saldo user ketika status berubah menjadi completed
            if ($wasteDeposit->isDirty('status') && $wasteDeposit->status === self::STATUS_COMPLETED) {
                if ($wasteDeposit->user) {
                    $wasteDeposit->user->updateBalance();
                }
            }
        });
    }

    public function getFormattedTotalWeightAttribute()
    {
        return strpos($this->total_weight, '.') !== false ? rtrim(rtrim($this->total_weight, '0'), '.') : $this->total_weight;
    }

    public function getFormattedTotalAmountAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            self::STATUS_COMPLETED => '<span class="badge bg-success">Selesai</span>',
            self::STATUS_PENDING => '<span class="badge bg-secondary">Pending</span>',
            self::STATUS_REJECTED => '<span class="badge bg-danger">Ditolak</span>',
            default => '<span class="badge bg-light text-dark">Unknown</span>',
        };
    }

    public function getWasteItemsListAttribute()
    {
        if (!is_array($this->waste_items)) {
            return '-';
        }

        return collect($this->waste_items)
            ->pluck('name')
            ->filter()
            ->implode(', ');
    }

    // Scope untuk filter berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope untuk filter berdasarkan user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope untuk pencarian berdasarkan nama user
    public function scopeSearchByUserName($query, $search)
    {
        return $query->whereHas('user', function ($userQuery) use ($search) {
            $userQuery->where('name', 'like', '%' . $search . '%');
        });
    }
}
