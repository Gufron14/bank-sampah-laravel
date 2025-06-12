<?php

use App\Livewire\Home;
use App\Livewire\Riwayat;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Profil;
use App\Livewire\SetorSampah;
use App\Livewire\Auth\Register;
use App\Livewire\Admin\JenisSampah;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\ListTarikSaldo;
use App\Livewire\Admin\ListSetorSampah;
use App\Livewire\Admin\ListNasabah;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', Home::class)->name('/');

Route::middleware('auth')->group(function () {
    Route::get('setor-sampah', SetorSampah::class)->name('setor-sampah');
    Route::get('riwayat', Riwayat::class)->name('riwayat');
    Route::get('profil', Profil::class)->name('profil');

    // Logout route
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('login');
    })->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
});

Route::middleware('admin')->group(function() {
    Route::prefix('admin')->group(function() {
        Route::get('dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('daftar-setor-sampah', ListSetorSampah::class)->name(('list-setor'));
        Route::get('daftar-tarik-saldo', ListTarikSaldo::class)->name(('list-tarik-saldo'));
        Route::get('jenis-sampah', JenisSampah::class)->name(('jenis-sampah'));
        Route::get('daftar-nasabah', ListNasabah::class)->name(('list-nasabah'));
    });
});

