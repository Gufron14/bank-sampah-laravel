<div>
    <div class="container-fluid text-bg-info p-4 text-center text-white">
        <h3>Riwayat Transaksi</h3>
        <span>Berupa Setor Sampah dan Tarik Saldo</span>
    </div>

    {{-- Filter Riwayat --}}
    <div class="container p-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="filterType" class="form-label">Filter Jenis Transaksi</label>
                        <select class="form-select" wire:model.live="filterType" id="filterType">
                            <option value="all">Semua Transaksi</option>
                            <option value="{{ \App\Models\TransactionHistory::TYPE_DEPOSIT }}">Setor Sampah</option>
                            <option value="{{ \App\Models\TransactionHistory::TYPE_WITHDRAWAL }}">Tarik Saldo</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="filterDate" class="form-label">Filter Tanggal</label>
                        <input type="date" class="form-control" wire:model.live="filterDate" id="filterDate">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Debug Info --}}
    {{-- @if (config('app.debug'))
        <div class="container p-4">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">Debug Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6>Transaction Debug:</h6>
                            <small>
                                Total Transactions: {{ $transactions->total() }}<br>
                                Current Page: {{ $transactions->currentPage() }}<br>
                                Filter Type: {{ $filterType }}<br>
                                Filter Date: {{ $filterDate ?: 'None' }}<br>
                                User ID: {{ Auth::id() }}
                            </small>
                        </div>
                        <div class="col-md-4">
                            <h6>Raw Data Check:</h6>
                            <small>
                                @php
                                    $wasteDeposits = \App\Models\WasteDeposit::where('user_id', Auth::id())->count();
                                    $balanceWithdrawals = \App\Models\BalanceWithdrawal::where(
                                        'user_id',
                                        Auth::id(),
                                    )->count();
                                    $transactionHistories = \App\Models\TransactionHistory::where(
                                        'user_id',
                                        Auth::id(),
                                    )->count();
                                @endphp
                                WasteDeposits: {{ $wasteDeposits }}<br>
                                BalanceWithdrawals: {{ $balanceWithdrawals }}<br>
                                TransactionHistories: {{ $transactionHistories }}
                            </small>
                        </div>
                        <div class="col-md-4">
                            <h6>Type Distribution:</h6>
                            <small>
                                @php
                                    $typeCount = $transactions->groupBy('type')->map->count();
                                @endphp
                                Deposit: {{ $typeCount->get('deposit', 0) }}<br>
                                Withdrawal: {{ $typeCount->get('withdrawal', 0) }}
                            </small>
                        </div>
                    </div>

                    @if ($transactions->count() > 0)
                        <h6 class="mt-3">Transaction Details:</h6>
                        @foreach ($transactions as $transaction)
                            <div
                                class="mt-2 p-2 border-start border-3 {{ $transaction->type === 'deposit' ? 'border-success' : 'border-warning' }}">
                                <small>
                                    <strong>ID:</strong> {{ $transaction->id }} |
                                    <strong>Type:</strong> {{ $transaction->type }} |
                                    <strong>Amount:</strong> {{ $transaction->amount }}<br>
                                    <strong>Reference Type:</strong> {{ $transaction->reference_type ?? 'NULL' }} |
                                    <strong>Reference ID:</strong> {{ $transaction->reference_id ?? 'NULL' }}<br>

                                    @if ($transaction->type === 'deposit')
                                        <strong>WasteDeposit:</strong>
                                        @if ($transaction->wasteDeposit)
                                            ID: {{ $transaction->wasteDeposit->id }},
                                            Status: {{ $transaction->wasteDeposit->status }},
                                            Items: {{ count($transaction->wasteDeposit->waste_items ?? []) }}
                                        @else
                                            <span class="text-danger">NULL (Ref:
                                                {{ $transaction->reference_type }}/{{ $transaction->reference_id }})</span>
                                        @endif
                                    @else
                                        <strong>BalanceWithdrawal:</strong>
                                        @if ($transaction->balanceWithdrawal)
                                            ID: {{ $transaction->balanceWithdrawal->id }},
                                            Status: {{ $transaction->balanceWithdrawal->status }},
                                            Code: {{ $transaction->balanceWithdrawal->withdrawal_code ?? 'NULL' }}
                                        @else
                                            <span class="text-danger">NULL (Ref:
                                                {{ $transaction->reference_type }}/{{ $transaction->reference_id }})</span>
                                        @endif
                                    @endif
                                </small>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info mt-3">
                            <strong>No TransactionHistory found!</strong><br>
                            @php
                                $recentWasteDeposits = \App\Models\WasteDeposit::where('user_id', Auth::id())
                                    ->latest()
                                    ->take(3)
                                    ->get();
                            @endphp
                            @if ($recentWasteDeposits->count() > 0)
                                <small>Recent WasteDeposits without TransactionHistory:</small><br>
                                @foreach ($recentWasteDeposits as $wd)
                                    <small>- ID: {{ $wd->id }}, Amount: {{ $wd->total_amount }}, Status:
                                        {{ $wd->status }}, Created: {{ $wd->created_at }}</small><br>
                                @endforeach
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('message'))
        <div class="container p-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif --}}

    {{-- Riwayat --}}
    <div class="container p-4">
        @if ($transactions->count() > 0)
            <div class="row g-4">
                @foreach ($transactions as $transaction)
                    @if ($transaction->type === \App\Models\TransactionHistory::TYPE_DEPOSIT)
                        {{-- Hapus Nanti --}}
                        {{-- @if ($transaction->wasteDeposit && config('app.debug'))
                            <div class="mt-2">
                                <small class="text-muted d-block mb-1">Ubah Status:</small>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button"
                                        class="btn btn-outline-warning btn-sm {{ $transaction->wasteDeposit->status === 'pending' ? 'active' : '' }}"
                                        wire:click="updateWasteDepositStatus({{ $transaction->wasteDeposit->id }}, 'pending')">
                                        Pending
                                    </button>
                                    <button type="button"
                                        class="btn btn-outline-info btn-sm {{ $transaction->wasteDeposit->status === 'processing' ? 'active' : '' }}"
                                        wire:click="updateWasteDepositStatus({{ $transaction->wasteDeposit->id }}, 'processing')">
                                        Processing
                                    </button>
                                    <button type="button"
                                        class="btn btn-outline-success btn-sm {{ $transaction->wasteDeposit->status === 'completed' ? 'active' : '' }}"
                                        wire:click="updateWasteDepositStatus({{ $transaction->wasteDeposit->id }}, 'completed')">
                                        Completed
                                    </button>
                                </div>
                            </div>
                        @endif --}}
                        
                        {{-- Jika Transaksi Setor Sampah --}}
                        <div class="col-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            @if ($transaction->wasteDeposit)
                                                <span
                                                    class="badge {{ $transaction->wasteDeposit->status === 'completed' ? 'text-bg-success' : ($transaction->wasteDeposit->status === 'pending' ? 'text-bg-warning' : 'text-bg-secondary') }}">
                                                    {{ ucfirst($transaction->wasteDeposit->status === 'pending' ? 'Menunggu Penjemputan' : ($transaction->wasteDeposit->status === 'completed' ? 'Selesai' : $transaction->wasteDeposit->status)) }}
                                                </span>
                                            @endif
                                        </div>

                                        <small
                                            class="text-secondary mt-0">{{ $transaction->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2 mb-0">
                                        <div>
                                            <h4>{{ $transaction->description }}</h4>
                                            @if ($transaction->wasteDeposit && $transaction->wasteDeposit->waste_items)
                                                <small class="text-muted">
                                                    {{ collect($transaction->wasteDeposit->waste_items)->pluck('waste_type_name')->join(', ') }}
                                                </small>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <span class="text-secondary">Pendapatan</span>
                                            <h4 class="text-success fw-bold">{{ $transaction->formatted_amount }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Hapus Nanti --}}
                        {{-- @if ($transaction->balanceWithdrawal && config('app.debug'))
                            <div class="mt-2">
                                <small class="text-muted d-block mb-1">Ubah Status:</small>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button"
                                        class="btn btn-outline-warning btn-sm {{ $transaction->balanceWithdrawal->status === 'pending' ? 'active' : '' }}"
                                        wire:click="updateBalanceWithdrawalStatus({{ $transaction->balanceWithdrawal->id }}, 'pending')">
                                        Pending
                                    </button>
                                    <button type="button"
                                        class="btn btn-outline-info btn-sm {{ $transaction->balanceWithdrawal->status === 'processing' ? 'active' : '' }}"
                                        wire:click="updateBalanceWithdrawalStatus({{ $transaction->balanceWithdrawal->id }}, 'processing')">
                                        Processing
                                    </button>
                                    <button type="button"
                                        class="btn btn-outline-success btn-sm {{ $transaction->balanceWithdrawal->status === 'completed' ? 'active' : '' }}"
                                        wire:click="updateBalanceWithdrawalStatus({{ $transaction->balanceWithdrawal->id }}, 'completed')">
                                        Completed
                                    </button>
                                    <button type="button"
                                        class="btn btn-outline-danger btn-sm {{ $transaction->balanceWithdrawal->status === 'rejected' ? 'active' : '' }}"
                                        wire:click="updateBalanceWithdrawalStatus({{ $transaction->balanceWithdrawal->id }}, 'rejected')">
                                        Rejected
                                    </button>
                                </div>
                            </div>
                        @endif --}}

                        {{-- Jika Transaksi Tarik Saldo --}}
                        <div class="col-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4 mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge text-bg-warning">Tarik Saldo</span>
                                        <small
                                            class="text-secondary">{{ $transaction->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center my-3">
                                        <h4 class="text-success fw-bold">{{ $transaction->formatted_amount }}</h4>

                                        {{-- Status section --}}
                                        <div>
                                            <span class="text-secondary me-2">Status:</span>
                                            @if ($transaction->balanceWithdrawal)
                                                @php
                                                    $status = $transaction->balanceWithdrawal->status ?? 'unknown';
                                                    $statusLabel = match ($status) {
                                                        'completed' => 'Selesai',
                                                        'pending' => 'Menunggu',
                                                        'processing' => 'Diproses',
                                                        'rejected' => 'Ditolak',
                                                        default => ucfirst($status),
                                                    };
                                                    $badgeClass = match ($status) {
                                                        'completed' => 'text-bg-success',
                                                        'pending' => 'text-bg-warning',
                                                        'processing' => 'text-bg-info',
                                                        'rejected' => 'text-bg-danger',
                                                        default => 'text-bg-secondary',
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                                            @else
                                                <span class="badge text-bg-secondary">No Reference Data</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <small class="text-muted">{{ $transaction->description }}</small>
                                        @if ($transaction->balanceWithdrawal && $transaction->balanceWithdrawal->status === 'completed'
                                            // && $transaction->balanceWithdrawal->transfer_proof
                                            )
                                        <button type="button" class="btn btn-sm btn-link p-0 mb-0"
                                            style="text-decoration: none;"
                                            wire:click="showTransactionDetail({{ $transaction->id }})"
                                            data-bs-toggle="modal" data-bs-target="#transferProofModal">
                                            <i class="fas fa-eye me-1"></i>
                                            Bukti Transfer
                                        </button>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Riwayat Transaksi</h5>
                        <p class="text-muted">Riwayat transaksi Anda akan muncul di sini setelah melakukan setor sampah
                            atau tarik saldo.</p>
                        <div class="mt-4">
                            <a href="{{ route('setor-sampah') }}" class="btn btn-success me-2">Setor Sampah</a>
                            <a href="{{ route('/') }}" class="btn btn-outline-primary">Kembali ke Beranda</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal Bukti Transfer --}}
    @if ($selectedTransaction && $selectedTransaction->balanceWithdrawal)
        <div class="modal fade" id="transferProofModal" tabindex="-1" aria-labelledby="transferProofModalLabel"
            aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="transferProofModalLabel">Bukti Transfer</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="mb-3">
                            {{-- <h6>Kode Penarikan: {{ $selectedTransaction->balanceWithdrawal->withdrawal_code }}</h6> --}}
                            <p class="text-muted">Jumlah: {{ $selectedTransaction->formatted_amount }}</p>
                            <p class="text-muted">Tanggal: {{ $selectedTransaction->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        @if ($selectedTransaction->balanceWithdrawal->transfer_proof)
                            <img src="{{ asset('storage/' . $selectedTransaction->balanceWithdrawal->transfer_proof) }}"
                                alt="Bukti Transfer" class="img-fluid rounded shadow" style="max-height: 500px;">
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Bukti transfer belum tersedia
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                            wire:click="closeModal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-modal', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('transferProofModal'));
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
</div>
