<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Daftar Nasabah Bank Sampah')]
#[Layout('components.layouts.admin-layout')]
class ListNasabah extends Component
{
    public function render()
    {
        $nasabahs = User::role('nasabah')->get();
        return view('livewire.admin.list-nasabah', compact('nasabahs'));
    }
}
