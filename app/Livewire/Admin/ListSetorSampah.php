<?php

namespace App\Livewire\Admin;

use App\Models\WasteDeposit;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Daftar Setor Sampah')]
#[Layout('components.layouts.admin-layout')]
class ListSetorSampah extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function approve($wasteDepositId)
    {
        try {
            $wasteDeposit = WasteDeposit::findOrFail($wasteDepositId);
            
            if ($wasteDeposit->status === 'pending') {
                $wasteDeposit->update(['status' => 'completed']);
                
                session()->flash('message', 'Setoran sampah berhasil disetujui!');
                session()->flash('type', 'success');
            }
        } catch (\Exception $e) {
            session()->flash('message', 'Terjadi kesalahan saat menyetujui setoran!');
            session()->flash('type', 'danger');
        }
    }

    public function reject($wasteDepositId)
    {
        try {
            $wasteDeposit = WasteDeposit::findOrFail($wasteDepositId);
            
            if ($wasteDeposit->status === 'pending') {
                $wasteDeposit->update(['status' => 'rejected']);
                
                session()->flash('message', 'Setoran sampah berhasil ditolak!');
                session()->flash('type', 'danger');
            }
        } catch (\Exception $e) {
            session()->flash('message', 'Terjadi kesalahan saat menolak setoran!');
            session()->flash('type', 'danger');
        }
    }

    public function render()
    {
        $query = WasteDeposit::with('user');

        // Apply search filter
        if (!empty($this->search)) {
            $query->whereHas('user', function ($userQuery) {
                $userQuery->where('name', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        $wasteDeposits = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.list-setor-sampah', [
            'wasteDeposits' => $wasteDeposits
        ]);
    }
}
