<div class="mt-4">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h2 mb-1">Daftar Jenis Sampah</h1>
        <p class="text-muted">Kelola jenis sampah dan harga per kilogram</p>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Actions Bar -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       class="form-control" 
                       placeholder="Cari jenis sampah...">
            </div>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" 
                    class="btn btn-primary" 
                    data-bs-toggle="modal" 
                    data-bs-target="#wasteTypeModal"
                    wire:click="openCreateModal">
                <i class="fas fa-plus me-2"></i>
                Tambah Jenis Sampah
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr class="text-center text-uppercase">
                            <th>Nama Jenis Sampah</th>
                            <th>Harga per Kg</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Tanggal Dibuat</th>
                            <th width="120" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wasteTypes as $wasteType)
                            <tr>
                                <td>
                                    <strong>{{ $wasteType->name }}</strong>
                                </td>
                                <td>
                                    <span class="text-success fw-bold">
                                        Rp {{ number_format($wasteType->price_per_kg, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                        {{ $wasteType->description ?: '-' }}
                                    </span>
                                </td>
                                <td>
                                    <button wire:click="toggleStatus({{ $wasteType->id }})" 
                                            class="btn btn-sm {{ $wasteType->is_active ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $wasteType->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </td>
                                <td class="text-center">
                                    <small class="text-muted">
                                        {{ $wasteType->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#wasteTypeModal"
                                                wire:click="openEditModal({{ $wasteType->id }})"
                                                title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                wire:click="delete({{ $wasteType->id }})"
                                                wire:confirm="Apakah Anda yakin ingin menghapus jenis sampah ini?"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        <p class="mb-1">Tidak ada data jenis sampah</p>
                                        @if($search)
                                            <small>Coba ubah kata kunci pencarian</small>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($wasteTypes->hasPages())
            <div class="card-footer">
                {{ $wasteTypes->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="wasteTypeModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $isEdit ? 'Edit Jenis Sampah' : 'Tambah Jenis Sampah' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <form wire:submit="save">
                    <div class="modal-body">
                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nama Jenis Sampah <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="name"
                                   wire:model="name" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Contoh: Kardus, Plastik, Besi">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Price per Kg -->
                        <div class="mb-3">
                            <label for="price_per_kg" class="form-label">
                                Harga per Kg (Rp) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                       id="price_per_kg"
                                       wire:model="price_per_kg" 
                                       step="0.01"
                                       min="0"
                                       class="form-control @error('price_per_kg') is-invalid @enderror"
                                       placeholder="0">
                                @error('price_per_kg')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea id="description"
                                      wire:model="description" 
                                      rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Deskripsi jenis sampah (opsional)"></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                       id="is_active"
                                       wire:model="is_active"
                                       class="form-check-input">
                                <label class="form-check-label" for="is_active">
                                    Aktif
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Jenis sampah yang aktif akan ditampilkan dalam sistem
                            </small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" 
                                class="btn btn-primary" 
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                {{ $isEdit ? 'Perbarui' : 'Simpan' }}
                            </span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Custom Styles -->
    <style>
        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
        }
        
        .btn-group .btn {
            border-radius: 0.375rem;
            margin-right: 2px;
        }
        
        .btn-group .btn:last-child {
            margin-right: 0;
        }
        
        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    
    <!-- JavaScript untuk Modal -->
    <script>
        document.addEventListener('livewire:init', function () {
            Livewire.on('close-modal', (modalId) => {
                const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
</div>

