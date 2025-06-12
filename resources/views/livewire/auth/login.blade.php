<div class="container p-5">
    <div class="card border-0 shadow-sm w-50 mx-auto">
        <div class="card-body p-5">
            <h4 class="text-center">Masuk</h4>
            
            @if (session()->has('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif

            @if (session()->has('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif

            <form wire:submit="login" class="mt-4">
                <div class="mb-3">
                    <label for="email" class="form-label text-secondary">Email *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" wire:model="email" placeholder="example@gmail.com">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label text-secondary">Password *</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" wire:model="password" placeholder="*******">
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" wire:model="remember">
                    <label class="form-check-label" for="remember">
                        Ingat saya
                    </label>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span>
                        Belum punya Akun?
                        <a href="{{ route('register') }}">Daftar Akun</a>
                    </span>
                    <button type="submit" class="btn btn-primary fw-bold mt-4" wire:loading.attr="disabled">
                        <span wire:loading.remove>Masuk</span>
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
