<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        // 'withdrawal_code',
        'amount',
        'status',
        'transfer_proof',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function transactionHistory()
    {
        return $this->morphOne(TransactionHistory::class, 'reference');
    }

    protected static function boot()
    {
        parent::boot();

        // static::creating(function ($model) {
        //     $model->withdrawal_code = 'WD-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
        // });

        // Buat transaction history saat withdrawal dibuat
        // static::created(function ($withdrawal) {
        //     TransactionHistory::create([
        //         'user_id' => $withdrawal->user_id,
        //         'type' => TransactionHistory::TYPE_WITHDRAWAL,
        //         'amount' => $withdrawal->amount,
        //         'description' => 'Penarikan Saldo - ' . $withdrawal->withdrawal_code,
        //         'reference_type' => BalanceWithdrawal::class,
        //         'reference_id' => $withdrawal->id,
        //     ]);
        // });

        static::updated(function ($withdrawal) {
            // Update saldo user ketika status withdrawal berubah
            if ($withdrawal->user) {
                $withdrawal->user->updateBalance();
            }
        });
    }
}
