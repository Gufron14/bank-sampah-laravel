<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

#[Title('Daftar Akun')]
class Register extends Component
{
    use WithFileUploads;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $phone = '';
    public $dusun = '';
    public $rt = '';
    public $rw = '';
    public $age = '';
    public $avatar;
    public $no_rek = '';
    public $user_type = 'nasabah'; // Default to nasabah

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|string|max:15',
            'dusun' => 'required|string|max:255',
            'rt' => 'required|numeric|min:1',
            'rw' => 'required|numeric|min:1',
            'age' => 'required|numeric|min:7|max:100',
            'avatar' => 'nullable|image|max:2048', // 2MB max
            'user_type' => 'required|in:admin,nasabah',
            'no_rek' => 'required|string|max:16',
        ];
    }

    protected $messages = [
        'name.required' => 'Nama lengkap wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 6 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'phone.required' => 'Nomor telepon wajib diisi.',
        'dusun.required' => 'Nama dusun wajib diisi.',
        'rt.required' => 'RT wajib diisi.',
        'rt.numeric' => 'RT harus berupa angka.',
        'rw.required' => 'RW wajib diisi.',
        'rw.numeric' => 'RW harus berupa angka.',
        'age.required' => 'Usia wajib diisi.',
        'age.numeric' => 'Usia harus berupa angka.',
        'age.min' => 'Usia minimal 17 tahun.',
        'age.max' => 'Usia maksimal 100 tahun.',
        'avatar.image' => 'File harus berupa gambar.',
        'avatar.max' => 'Ukuran gambar maksimal 2MB.',
        'user_type.required' => 'Tipe pengguna wajib dipilih.',
        'user_type.in' => 'Tipe pengguna tidak valid.',
        'no_rek.required' => 'Nomor rekening wajib diisi.',
    ];

    public function register()
    {
        $this->validate();

        try {
            $avatarPath = null;
            if ($this->avatar) {
                $avatarPath = $this->avatar->store('avatars', 'public');
            }

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'phone' => $this->phone,
                'dusun' => $this->dusun,
                'rt' => $this->rt,
                'rw' => $this->rw,
                'age' => $this->age,
                'balance' => 0,
                'no_rek' => $this->no_rek,
                'is_active' => $this->user_type === 'admin' ? false : true, // Admin needs approval
                'avatar' => $avatarPath,
            ]);

            // Assign role
            // $user->assignRole($this->user_type);

            // if ($this->user_type === 'admin') {
            //     session()->flash('success', 'Pendaftaran admin berhasil! Menunggu persetujuan administrator.');
            // } else {
            //     session()->flash('success', 'Pendaftaran berhasil! Silakan login dengan akun Anda.');
            // }

            return redirect()->route('login');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
