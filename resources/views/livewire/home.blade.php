<div>
    <!-- Hero -->
    <header>
        <!-- Background image -->
        <div class="text-center bg-image"
            style="
      background-image: url('https://www.denpasarkota.go.id/public/uploads/berita/Berita_230707070753_bank-sampah-pelita-bagikan-tas-ramah-lingkungan-ajak-warga-kelola-sampah-berbasis-sumber.jpg');
      height: 400px;
    ">
            <div class="mask" style="background-color: rgba(0, 0, 0, 0.6); width: 100%; height: 100%;">
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="col-5 text-white">
                        <h3 class="text-warning">Selamat Datang di Website Bank Sampah Desa Tanggulangin - Kadungora</h3>
                        <span>Melalui gerakan pengelolaan sampah berbasis masyarakat, kami berkomitmen menciptakan
                            lingkungan yang bersih, sehat, dan bernilai ekonomi demi kesejahteraan warga desa.</span> <br>
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-lg btn-warning fw-bold mt-5">Masuk</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
        <!-- Background image -->
    </header>
    <!-- Hero -->

    @if (session()->has('success'))
    <div class="container py-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif


    @auth
        <div class="container py-5">
            <div class="d-flex gap-3 mx-5">
                <div class="col">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body mx-5 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-secondary">Saldo</span>
                                <h4 class="fw-bold">Rp{{ number_format($userBalance, 0, ',', '.') }}</h4>
                            </div>
                            <div>
                                <button class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#tarikModal"
                                    @if ($userBalance <= 0) disabled @endif>
                                    Tarik Saldo
                                </button>
                                @if ($userBalance <= 0)
                                    <small class="text-muted d-block mt-1">Saldo tidak mencukupi</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body mx-5 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-secondary">Sampah Terkumpul</span>
                                <h5 class="fw-bold">
                                    {{ number_format(auth()->user()->wasteDeposits->where('status', 'completed')->sum('total_weight'), 1) }}
                                    Kg</h5>
                            </div>
                            <div>
                                <a href="{{ route('setor-sampah') }}" class="btn btn-success fw-bold">Setor Sampah</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth


    {{-- Modal Tarik Saldo --}}
    <div class="modal fade" id="tarikModal" tabindex="-1" aria-labelledby="tarikModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tarikModalLabel">Tarik Saldo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="withdrawBalance">
                    <div class="modal-body">
                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="mb-3">
                            <small class="text-muted">Saldo tersedia:
                                Rp{{ number_format($userBalance, 0, ',', '.') }}</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nominal" class="form-label text-secondary">Nominal</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number"
                                    class="form-control @error('nominal') is-invalid @enderror"
                                    wire:model="withdrawalAmount" placeholder="Masukkan nominal">
                            </div>
                            @error('nominal')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" @if ($userBalance <= 0) disabled @endif
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>Tarik Saldo Sekarang</span>
                            <span wire:loading>Memproses...</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-modal', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('tarikModal'));
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
</div>
