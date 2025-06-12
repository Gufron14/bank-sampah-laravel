<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\TransactionHistory;
use Illuminate\Support\Facades\Auth;

#[Title('Riwayat')]
class Riwayat extends Component
{
    use WithPagination;

    public $filterType = 'all';
    public $filterDate = '';
    public $selectedTransaction = null;
    public $debugInfo = [];

    protected $paginationTheme = 'bootstrap';

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterDate()
    {
        $this->resetPage();
    }

    public function showTransactionDetail($transactionId)
    {
        $this->selectedTransaction = TransactionHistory::with(['user', 'balanceWithdrawal', 'wasteDeposit'])
            ->where('user_id', Auth::id())
            ->find($transactionId);
    }

    public function closeModal()
    {
        $this->selectedTransaction = null;
    }

    public function getTransactionsProperty()
    {
        $query = TransactionHistory::with(['user', 'balanceWithdrawal', 'wasteDeposit'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Filter by type
        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        // Filter by date
        if ($this->filterDate) {
            $query->whereDate('created_at', $this->filterDate);
        }

        return $query->paginate(10);
    }

    // Tambahkan method ini di class Riwayat

public function updateWasteDepositStatus($wasteDepositId, $status)
{
    $wasteDeposit = \App\Models\WasteDeposit::where('user_id', Auth::id())
        ->find($wasteDepositId);
    
    if ($wasteDeposit) {
        $wasteDeposit->update(['status' => $status]);
        session()->flash('message', 'Status setor sampah berhasil diubah ke ' . $status);
    }
}

public function updateBalanceWithdrawalStatus($withdrawalId, $status)
{
    $withdrawal = \App\Models\BalanceWithdrawal::where('user_id', Auth::id())
        ->find($withdrawalId);
    
    if ($withdrawal) {
        $withdrawal->update(['status' => $status]);
        session()->flash('message', 'Status penarikan saldo berhasil diubah ke ' . $status);
    }
}


    public function render()
    {
        return view('livewire.riwayat', [
            'transactions' => $this->transactions,
        ]);
    }
}
