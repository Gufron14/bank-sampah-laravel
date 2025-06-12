<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use HasFactory;

    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAWAL = 'withdrawal';

    protected $fillable = ['user_id', 'type', 'amount', 'description', 'reference_id', 'status'];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

public function getReferenceDataAttribute()
{
    if ($this->type === self::TYPE_DEPOSIT) {
        return $this->wasteDeposit;
    } elseif ($this->type === self::TYPE_WITHDRAWAL) {
        return $this->balanceWithdrawal;
    }

    return null;
}

public function reference()
{
    return $this->morphTo();
}


    public function balanceWithdrawal()
    {
        return $this->belongsTo(\App\Models\BalanceWithdrawal::class, 'reference_id');
    }

    public function wasteDeposit()
    {
        return $this->belongsTo(\App\Models\WasteDeposit::class, 'reference_id');
    }

    // Accessor untuk format amount
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    // Accessor untuk type label
    public function getTypeLabelAttribute()
    {
        return $this->type === self::TYPE_DEPOSIT ? 'Setor Sampah' : 'Tarik Saldo';
    }

    // Accessor untuk type badge class
    public function getTypeBadgeClassAttribute()
    {
        return $this->type === self::TYPE_DEPOSIT ? 'badge text-bg-success' : 'badge text-bg-warning';
    }
}
