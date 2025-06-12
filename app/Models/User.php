<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'password', 'phone', 'dusun', 'rt', 'rw', 'age', 'avatar', 'no_rek', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'balance' => 'decimal:2',
        'next_pickup_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function wasteDeposits()
    {
        return $this->hasMany(WasteDeposit::class);
    }

    public function balanceWithdrawals()
    {
        return $this->hasMany(BalanceWithdrawal::class);
    }

    public function transactionHistories()
    {
        return $this->hasMany(TransactionHistory::class);
    }

    // Helper method to get avatar URL
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    // Method untuk mendapatkan riwayat transaksi terbaru
    public function getRecentTransactionsAttribute()
    {
        return $this->transactionHistories()->orderBy('created_at', 'desc')->limit(10)->get();
    }

    // Method untuk mendapatkan total deposit
    public function getTotalDepositsAttribute()
    {
        return $this->transactionHistories()->where('type', TransactionHistory::TYPE_DEPOSIT)->sum('amount');
    }

    // Method untuk mendapatkan total withdrawal
    public function getTotalWithdrawalsAttribute()
    {
        return $this->transactionHistories()->where('type', TransactionHistory::TYPE_WITHDRAWAL)->sum('amount');
    }

    // Method untuk format saldo
    public function getFormattedBalanceAttribute()
    {
        return 'Rp ' . number_format($this->current_balance, 0, ',', '.');
    }

    // Method untuk menghitung saldo real-time
    public function getCurrentBalanceAttribute()
    {
        $totalDeposits = $this->transactionHistories()->where('type', TransactionHistory::TYPE_DEPOSIT)->sum('amount');

        $totalWithdrawals = $this->transactionHistories()->where('type', TransactionHistory::TYPE_WITHDRAWAL)->sum('amount');

        return $totalDeposits - $totalWithdrawals;
    }

    public function getBalanceAttribute()
    {
        $deposit = $this->wasteDeposits()->where('status', 'completed')->sum('total_amount');
        $withdrawal = $this->balanceWithdrawals()->where('status', 'completed')->sum('amount');

        return $deposit - $withdrawal;
    }

    public function updateBalance()
    {
        $deposit = $this->wasteDeposits()->where('status', 'completed')->sum('total_amount');
        $withdrawal = $this->balanceWithdrawals()->where('status', 'completed')->sum('amount');

        $this->balance = $deposit - $withdrawal;
        $this->save();
    }
}
