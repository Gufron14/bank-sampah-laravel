<div class="pt-4">
    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="alert alert-{{ session('type', 'info') }} alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Daftar Permintaan Tarik Saldo</h4>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Cari Nasabah</label>
                    <input type="text" class="form-control" id="search" wire:model.live="search" 
                           placeholder="Cari berdasarkan nama atau email...">
                </div>
                <div class="col-md-3">
                    <label for="statusFilter" class="form-label">Filter Status</label>
                    <select class="form-select" id="statusFilter" wire:model.live="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu</option>
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="perPage" class="form-label">Tampilkan</label>
                    <select class="form-select" id="perPage" wire:model.live="perPage">
                        <option value="10">10 per halaman</option>
                        <option value="25">25 per halaman</option>
                        <option value="50">50 per halaman</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body">
            @if($withdrawals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr class="text-center text-uppercase">
                                <th>No</th>
                                <th>Nasabah</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tgl Pengajuan</th>
                                {{-- <th>Diproses Oleh</th> --}}
                                <th>Tgl Diproses</th>
                                <th>Bukti Transfer</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $index => $withdrawal)
                                <tr class="align-middle text-center">
                                    <td>{{ $withdrawals->firstItem() + $index }}</td>
                                    <td class="text-start">
                                        <div>
                                            <strong>{{ $withdrawal->user->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-primary">
                                            Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                    <td>
                                        @if($withdrawal->status === 'pending')
                                            <span class="badge bg-secondary">Menunggu</span>
                                        @elseif($withdrawal->status === 'completed')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($withdrawal->status === 'rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    {{-- <td>
                                        @if($withdrawal->processor)
                                            <small>{{ $withdrawal->processor->name }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td> --}}
                                    <td>
                                        @if($withdrawal->processed_at)
                                            <small>{{ $withdrawal->processed_at->format('d/m/Y H:i') }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($withdrawal->transfer_proof)
                                            <a href="{{ Storage::url($withdrawal->transfer_proof) }}" 
                                               target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        @else
                                            <small class="text-muted">Belum ada</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($withdrawal->status === 'pending')
                                            <div class="btn-group" role="group">
                                                <button type="button" 
                                                        class="btn btn-sm btn-success"
                                                        wire:click="approveWithdrawal({{ $withdrawal->id }})"
                                                        wire:confirm="Apakah Anda yakin ingin menyetujui permintaan penarikan ini?">
                                                    <i class="fas fa-check"></i> Setuju
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger"
                                                        wire:click="rejectWithdrawal({{ $withdrawal->id }})"
                                                        wire:confirm="Apakah Anda yakin ingin menolak permintaan penarikan ini?">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-muted">Sudah diproses</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Menampilkan {{ $withdrawals->firstItem() }} sampai {{ $withdrawals->lastItem() }} 
                            dari {{ $withdrawals->total() }} data
                        </small>
                    </div>
                    <div>
                        {{ $withdrawals->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada permintaan penarikan</h5>
                    <p class="text-muted">
                        @if($search || $statusFilter)
                            Tidak ada data yang sesuai dengan filter yang dipilih.
                        @else
                            Belum ada nasabah yang mengajukan permintaan penarikan saldo.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- Loading State --}}
    <div wire:loading class="position-fixed top-50 start-50 translate-middle">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <style>
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem;
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    

</style>
</div>

{{-- Custom Styles --}}

