<?php

namespace App\Livewire\Admin;

use App\Models\WasteType;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Title('Daftar Jenis Sampah')]
#[Layout('components.layouts.admin-layout')]
class JenisSampah extends Component
{
    use WithPagination;

    public $name = '';
    public $price_per_kg = '';
    public $description = '';
    public $is_active = true;
    public $wasteTypeId = null;
    public $isEdit = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'price_per_kg' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'is_active' => 'boolean'
    ];

    protected $messages = [
        'name.required' => 'Nama jenis sampah wajib diisi.',
        'name.max' => 'Nama jenis sampah maksimal 255 karakter.',
        'price_per_kg.required' => 'Harga per kg wajib diisi.',
        'price_per_kg.numeric' => 'Harga per kg harus berupa angka.',
        'price_per_kg.min' => 'Harga per kg tidak boleh kurang dari 0.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEdit = false;
    }

    public function openEditModal($id)
    {
        $wasteType = WasteType::findOrFail($id);
        $this->wasteTypeId = $wasteType->id;
        $this->name = $wasteType->name;
        $this->price_per_kg = $wasteType->price_per_kg;
        $this->description = $wasteType->description;
        $this->is_active = $wasteType->is_active;
        $this->isEdit = true;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->isEdit) {
                $wasteType = WasteType::findOrFail($this->wasteTypeId);
                $wasteType->update([
                    'name' => $this->name,
                    'price_per_kg' => $this->price_per_kg,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);
                session()->flash('success', 'Jenis sampah berhasil diperbarui.');
            } else {
                WasteType::create([
                    'name' => $this->name,
                    'price_per_kg' => $this->price_per_kg,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);
                session()->flash('success', 'Jenis sampah berhasil ditambahkan.');
            }

            $this->resetForm();
            $this->dispatch('close-modal', 'wasteTypeModal');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            WasteType::findOrFail($id)->delete();
            session()->flash('success', 'Jenis sampah berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $wasteType = WasteType::findOrFail($id);
            $wasteType->update(['is_active' => !$wasteType->is_active]);
            session()->flash('success', 'Status jenis sampah berhasil diubah.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->name = '';
        $this->price_per_kg = '';
        $this->description = '';
        $this->is_active = true;
        $this->wasteTypeId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $wasteTypes = WasteType::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.jenis-sampah', compact('wasteTypes'));
    }
}

