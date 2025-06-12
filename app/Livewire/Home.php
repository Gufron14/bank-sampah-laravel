<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\BalanceWithdrawal;
use App\Models\TransactionHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Title('Beranda')]
class Home extends Component
{
    public $withdrawalAmount;
    public $userBalance = 0;

    protected $rules = [
        'withdrawalAmount' => 'required|numeric|min:1000|max:1000000',
    ];

    protected $messages = [
        'withdrawalAmount.required' => 'Nominal harus diisi',
        'withdrawalAmount.numeric' => 'Nominal harus berupa angka',
        'withdrawalAmount.min' => 'Minimal penarikan Rp 1.000',
        'withdrawalAmount.max' => 'Maksimal penarikan Rp 1.000.000',
    ];

    public function mount()
    {
        $this->calculateUserBalance();
    }

    public function calculateUserBalance()
    {
        $user = Auth::user();

        // Hanya hitung saldo dari WasteDeposit yang completed
        $totalDeposits = \App\Models\WasteDeposit::where('user_id', Auth::id())->where('status', 'completed')->sum('total_amount');

        $totalWithdrawals = BalanceWithdrawal::where('user_id', Auth::id())->where('status', 'completed')->sum('amount');

        $saldo = $totalDeposits - $totalWithdrawals;
        $this->userBalance = $saldo > 0 ? $saldo : 0;

        // Update saldo di database user
        $user = Auth::user();
        if ($user && $user instanceof \App\Models\User) {
            $user->balance = $this->userBalance;
            $user->save();
        }
    }

    public function withdrawBalance()
    {
        try {
            $this->validate();

            if (!Auth::check()) {
                session()->flash('error', 'Anda harus login terlebih dahulu');
                return;
            }

            if ($this->withdrawalAmount > $this->userBalance) {
                $this->addError('nominal', 'Saldo tidak mencukupi');
                return;
            }

            DB::beginTransaction();

            // Buat record penarikan
            $withdrawal = BalanceWithdrawal::create([
                'user_id' => Auth::id(),
                'amount' => $this->withdrawalAmount,
                'status' => 'pending',
            ]);

            $balanceBefore = $this->userBalance;
            $balanceAfter = $balanceBefore - $this->withdrawalAmount;

            // Buat transaction history
            TransactionHistory::create([
                'user_id' => Auth::id(),
                'type' => TransactionHistory::TYPE_WITHDRAWAL,
                'amount' => $this->withdrawalAmount,
                'description' => 'Sisa saldo: Rp ' . number_format($balanceAfter, 0, ',', '.'),
                'reference_id' => $withdrawal->id,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
            ]);

            // Update saldo user
            $this->calculateUserBalance();

            DB::commit();

            session()->flash('success', 'Permintaan penarikan saldo berhasil diajukan, Silakan tunggu konfirmasi admin');

            $this->reset('withdrawalAmount');
            $this->dispatch('close-modal');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Tambahkan method atau property ini jika belum ada
public function getWasteDepositsProperty()
{
    return (object) [
        'total_weight' => \App\Models\WasteDeposit::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->sum('total_weight')
    ];
}

    public function getUserBalanceProperty()
    {
        return \App\Models\WasteDeposit::where('user_id', Auth::id())->where('status', 'completed')->sum('total_amount');
    }

    public function render()
    {
        return view('livewire.home');
    }
}
