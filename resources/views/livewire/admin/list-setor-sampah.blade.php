<div class="pt-4">
    <h3 class="mb-3">Daftar Setor Sampah</h3>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="alert alert-{{ session('type', 'info') }} alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0">
                        Daftar Setor Sampah Nasabah
                    </h5>
                </div>
                <div class="col-auto">
                    <div class="row g-2">
                        <div class="col-auto">
                            <input type="text" class="form-control form-control-sm"
                                placeholder="Cari nama nasabah..." wire:model.live="search">
                        </div>
                        <div class="col-auto">
                            <select class="form-select form-select-sm" wire:model.live="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Selesai</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center text-uppercase bg-light">
                            <th>No</th>
                            <th>Tgl Setor</th>
                            <th>Nama Nasabah</th>
                            <th>Jenis Sampah</th>
                            <th>Jumlah</th>
                            <th>Pendapatan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($wasteDeposits) && $wasteDeposits->count() > 0)
                            @foreach ($wasteDeposits as $index => $deposit)
                                <tr class="text-center align-middle">
                                    <td>{{ ($wasteDeposits->currentPage() - 1) * $wasteDeposits->perPage() + $index + 1 }}
                                    </td>
                                    <td>{{ $deposit->created_at->format('d-m-Y') }}</td>
                                    <td class="text-start">{{ $deposit->user->name ?? 'N/A' }}</td>
                                    <td>
                                        @if (!empty($deposit->waste_items) && is_array($deposit->waste_items))
                                            @foreach ($deposit->waste_items as $item)
                                                <span class="badge bg-light text-dark me-1">
                                                    {{ $item['waste_type_name'] ?? 'Unknown' }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>




                                    <td>{{ $deposit->formatted_total_weight }} kg</td>
                                    <td>Rp {{ number_format($deposit->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @switch($deposit->status)
                                            @case('completed')
                                                <span class="badge bg-success">Selesai</span>
                                            @break

                                            @case('pending')
                                                <span class="badge bg-secondary">Pending</span>
                                            @break

                                            @case('rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @break

                                            @default
                                                <span class="badge bg-light text-dark">{{ ucfirst($deposit->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if ($deposit->status === 'pending')
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-success"
                                                    wire:click="approve({{ $deposit->id }})"
                                                    wire:confirm="Apakah Anda yakin ingin menyetujui setoran ini?"
                                                    wire:loading.attr="disabled">
                                                    <i class="fas fa-check me-2"></i>Setuju
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="reject({{ $deposit->id }})"
                                                    wire:confirm="Apakah Anda yakin ingin menolak setoran ini?"
                                                    wire:loading.attr="disabled">
                                                    <i class="fas fa-times me-2"></i>Tolak
                                                </button>
                                            </div>
                                        @else
                                            <a href="" class="btn btn-sm btn-info fw-bold">Detail Nasabah</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <br>
                                    @if (!empty($search) || !empty($statusFilter))
                                        Tidak ada data yang sesuai dengan filter
                                    @else
                                        Tidak ada data setoran sampah
                                    @endif
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if (isset($wasteDeposits) && method_exists($wasteDeposits, 'hasPages') && $wasteDeposits->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $wasteDeposits->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Loading Indicator --}}
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
