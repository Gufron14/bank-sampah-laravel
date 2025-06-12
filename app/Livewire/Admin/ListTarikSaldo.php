<?php

namespace App\Livewire\Admin;

use App\Models\BalanceWithdrawal;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Daftar Permintaan Tarik Saldo')]
#[Layout('components.layouts.admin-layout')]
class ListTarikSaldo extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function approveWithdrawal($withdrawalId)
    {
        try {
            $withdrawal = BalanceWithdrawal::findOrFail($withdrawalId);
            
            if ($withdrawal->status !== 'pending') {
                session()->flash('message', 'Permintaan penarikan sudah diproses sebelumnya.');
                session()->flash('type', 'warning');
                return;
            }

            $withdrawal->update([
                'status' => 'completed',
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            session()->flash('message', 'Permintaan penarikan berhasil disetujui.');
            session()->flash('type', 'success');
        } catch (\Exception $e) {
            session()->flash('message', 'Terjadi kesalahan: ' . $e->getMessage());
            session()->flash('type', 'danger');
        }
    }

    public function rejectWithdrawal($withdrawalId)
    {
        try {
            $withdrawal = BalanceWithdrawal::findOrFail($withdrawalId);
            
            if ($withdrawal->status !== 'pending') {
                session()->flash('message', 'Permintaan penarikan sudah diproses sebelumnya.');
                session()->flash('type', 'warning');
                return;
            }

            $withdrawal->update([
                'status' => 'rejected',
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            session()->flash('message', 'Permintaan penarikan berhasil ditolak.');
            session()->flash('type', 'success');
        } catch (\Exception $e) {
            session()->flash('message', 'Terjadi kesalahan: ' . $e->getMessage());
            session()->flash('type', 'danger');
        }
    }

    public function render()
    {
        $withdrawals = BalanceWithdrawal::with(['user', 'processor'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.list-tarik-saldo', [
            'withdrawals' => $withdrawals
        ]);
    }
}
