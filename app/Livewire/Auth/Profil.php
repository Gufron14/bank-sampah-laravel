<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class Profil extends Component
{
    use WithFileUploads;

    public $editMode = false;
    public $name, $email, $phone, $dusun, $rt, $rw;
    public $avatar, $avatarPreview;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->dusun = $user->dusun;
        $this->rt = $user->rt;
        $this->rw = $user->rw;
    }

    public function updatedAvatar()
    {
        $this->avatarPreview = $this->avatar->temporaryUrl();
    }

    public function enableEdit()
    {
        $this->editMode = true;
    }

    public function cancelEdit()
    {
        $this->editMode = false;
        $this->mount(); // Reset data
        $this->avatar = null;
        $this->avatarPreview = null;
    }

    public function save()
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'dusun' => 'nullable|string|max:255',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user->name = $this->name;
        $user->phone = $this->phone;
        $user->dusun = $this->dusun;
        $user->rt = $this->rt;
        $user->rw = $this->rw;

        if ($this->avatar) {
            $path = $this->avatar->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();
        Auth::setUser($user->fresh());

        $this->editMode = false;
        session()->flash('success', 'Profil berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.auth.profil');
    }
}