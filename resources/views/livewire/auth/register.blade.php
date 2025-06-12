<div class="container p-5">
    <div class="d-flex justify-content-center">
        <div class="card border-0 shadow-sm w-100">
            <div class="card-body p-5">
                <h4>Daftar Akun</h4>

                @if (session()->has('error'))
                    <div class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                @endif

                <form wire:submit="register" class="mt-4">
                    <!-- User Type Selection -->
                    {{-- <div class="mb-4">
                        <label class="form-label text-secondary">Tipe Pengguna *</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="user_type" id="nasabah" 
                                       wire:model="user_type" value="nasabah">
                                <label class="form-check-label" for="nasabah">
                                    Nasabah
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="user_type" id="admin" 
                                       wire:model="user_type" value="admin">
                                <label class="form-check-label" for="admin">
                                    Admin
                                </label>
                            </div>
                        </div>
                        @error('user_type')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    <div class="d-flex gap-5 justify-content-between align-items-start">
                        <div class="col">
                            <div class="mb-3">
                                <label for="name" class="form-label text-secondary">Nama Lengkap *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" wire:model="name" placeholder="Masukan nama lengkap">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label text-secondary">Nomor Telepon *</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" wire:model="phone" placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-3 mb-3">
                                <div class="col-7">
                                    <label for="dusun" class="form-label text-secondary">Nama Dusun *</label>
                                    <input type="text" class="form-control @error('dusun') is-invalid @enderror" 
                                           id="dusun" wire:model="dusun" placeholder="Masukan nama dusun">
                                    @error('dusun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-2">
                                    <label for="rt" class="form-label text-secondary">RT *</label>
                                    <input type="number" class="form-control @error('rt') is-invalid @enderror" 
                                           id="rt" wire:model="rt" min="1">
                                    @error('rt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-2">
                                    <label for="rw" class="form-label text-secondary">RW *</label>
                                    <input type="number" class="form-control @error('rw') is-invalid @enderror" 
                                           id="rw" wire:model="rw" min="1">
                                    @error('rw')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-3">
                                <div class="col-3">
                                    <label for="age" class="form-label text-secondary">Usia *</label>
                                    <input type="number" class="form-control @error('age') is-invalid @enderror" 
                                           id="age" wire:model="age" placeholder="Usia" min="17" max="100">
                                    @error('age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-9">
                                    <label for="no_rek" class="form-label text-secondary">Nomor Rekening *</label>
                                    <input type="number" class="form-control @error('no_rek') is-invalid @enderror" 
                                           id="no_rek" wire:model="no_rek" placeholder="Bisa E-Wallet atau Bank">
                                    @error('no_rek')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="mb-3">
                                <label for="email" class="form-label text-secondary">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" wire:model="email" placeholder="example@gmail.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label text-secondary">Password *</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" wire:model="password" placeholder="*******">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label text-secondary">Konfirmasi Password *</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" wire:model="password_confirmation" placeholder="*******">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="avatar" class="form-label text-secondary">Foto Profil</label>
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                       id="avatar" wire:model="avatar" accept="image/*">
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                @if ($avatar)
                                    <div class="mt-2">
                                        <img src="{{ $avatar->temporaryUrl() }}" class="img-thumbnail" width="100">
                                        <small class="text-muted d-block">Preview foto profil</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span>
                            Sudah punya Akun? 
                            <a href="{{ route('login') }}">Masuk</a>
                        </span>
                        <button type="submit" class="btn btn-primary fw-bold mt-4" wire:loading.attr="disabled">
                            <span wire:loading.remove>Daftar Sekarang</span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
