<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WasteType;
use App\Models\WasteDeposit;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

#[Title('Setor Sampah')]

class SetorSampah extends Component
{
    public $wasteTypes = [];
    public $selectedWasteTypes = [];
    public $wasteWeights = [];
    public $selectAll = false;
    public $totalAmount = 0;
    public $totalWeight = 0;

    public function mount()
    {
        $this->loadWasteTypes();
    }

    public function loadWasteTypes()
    {
        $this->wasteTypes = WasteType::all();
        
        // Initialize arrays
        foreach ($this->wasteTypes as $wasteType) {
            $this->selectedWasteTypes[$wasteType->id] = false;
            $this->wasteWeights[$wasteType->id] = 0;
        }
    }

    public function updatedSelectAll()
    {
        foreach ($this->wasteTypes as $wasteType) {
            $this->selectedWasteTypes[$wasteType->id] = $this->selectAll;
            if (!$this->selectAll) {
                $this->wasteWeights[$wasteType->id] = 0;
            }
        }
        $this->calculateTotals();
    }

    public function updatedSelectedWasteTypes($value, $wasteTypeId)
    {
        if (!$value) {
            $this->wasteWeights[$wasteTypeId] = 0;
        }
        
        // Update select all checkbox
        $this->selectAll = count(array_filter($this->selectedWasteTypes)) === count($this->wasteTypes);
        
        $this->calculateTotals();
    }

    public function updatedWasteWeights()
    {
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->totalAmount = 0;
        $this->totalWeight = 0;

        foreach ($this->wasteTypes as $wasteType) {
            if ($this->selectedWasteTypes[$wasteType->id] && $this->wasteWeights[$wasteType->id] > 0) {
                $weight = (float) $this->wasteWeights[$wasteType->id];
                $this->totalAmount += $weight * $wasteType->price_per_kg;
                $this->totalWeight += $weight;
            }
        }
    }

    public function getWasteTotal($wasteTypeId)
    {
        // Fix: Use firstWhere instead of find for Collection
        $wasteType = collect($this->wasteTypes)->firstWhere('id', $wasteTypeId);
        
        if ($wasteType && isset($this->selectedWasteTypes[$wasteTypeId]) && $this->selectedWasteTypes[$wasteTypeId] && isset($this->wasteWeights[$wasteTypeId]) && $this->wasteWeights[$wasteTypeId] > 0) {
            return (float) $this->wasteWeights[$wasteTypeId] * $wasteType->price_per_kg;
        }
        return 0;
    }

    public function submitDeposit()
    {
        // Validation
        $hasSelectedWaste = false;
        $wasteItems = [];

        foreach ($this->wasteTypes as $wasteType) {
            if (isset($this->selectedWasteTypes[$wasteType->id]) && $this->selectedWasteTypes[$wasteType->id]) {
                $this->validate([
                    "wasteWeights.{$wasteType->id}" => 'required|numeric|min:0.1'
                ], [
                    "wasteWeights.{$wasteType->id}.required" => "Berat {$wasteType->name} harus diisi",
                    "wasteWeights.{$wasteType->id}.numeric" => "Berat {$wasteType->name} harus berupa angka",
                    "wasteWeights.{$wasteType->id}.min" => "Berat {$wasteType->name} minimal 0.1 kg"
                ]);

                $hasSelectedWaste = true;
                $wasteItems[] = [
                    'waste_type_id' => $wasteType->id,
                    'waste_type_name' => $wasteType->name,
                    'weight' => (float) $this->wasteWeights[$wasteType->id],
                    'price_per_kg' => $wasteType->price_per_kg,
                    'total' => $this->getWasteTotal($wasteType->id)
                ];
            }
        }

        if (!$hasSelectedWaste) {
            session()->flash('error', 'Pilih minimal satu jenis sampah untuk disetor');
            return;
        }

        // Create waste deposit
        WasteDeposit::create([
            'user_id' => Auth::id(),
            'waste_items' => $wasteItems,
            'total_weight' => $this->totalWeight,
            'total_amount' => $this->totalAmount,
            'status' => 'pending'
        ]);


        // Reset form
        $this->reset(['selectedWasteTypes', 'wasteWeights', 'selectAll', 'totalAmount', 'totalWeight']);
        $this->loadWasteTypes();

        session()->flash('success', 'Setor sampah berhasil diajukan! Menunggu konfirmasi admin.');
    }

    public function render()
    {
        return view('livewire.setor-sampah');
    }
}
