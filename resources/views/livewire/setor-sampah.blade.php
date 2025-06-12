<div>
    <div class="container-fluid text-bg-info p-4 text-center text-white">
        <h3>Input Setor Sampah</h3>
        <span>Jenis Sampah yang bisa diinput berupa {{ $wasteTypes->pluck('name')->join(', ') }}</span>
    </div>

    {{-- Alert Sukses dan Gagal --}}
    @if (session()->has('success'))
        <div class="container p-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="container p-4">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="container p-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form wire:submit.prevent="submitDeposit">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr class="align-middle text-center">
                                    <th class="text-center">
                                        {{-- Ceklis Semua Jenis Sampah --}}
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               wire:model.live="selectAll"
                                               id="selectAll">
                                        <label for="selectAll" class="form-check-label small">Semua</label>
                                    </th>
                                    <th>Jenis Sampah</th>
                                    <th>Berat (Kg)</th>
                                    <th>Harga per Kg</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wasteTypes as $wasteType)
                                    <tr class="align-middle">
                                        <td class="text-center">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   wire:model.live="selectedWasteTypes.{{ $wasteType->id }}"
                                                   id="waste_{{ $wasteType->id }}">
                                        </td>
                                        <td>
                                            <label for="waste_{{ $wasteType->id }}" class="form-label mb-0">
                                                {{ $wasteType->name }}
                                            </label>
                                            @if($wasteType->description)
                                                <small class="text-muted d-block">{{ $wasteType->description }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   class="form-control @error('wasteWeights.'.$wasteType->id) is-invalid @enderror" 
                                                   placeholder="Berat"
                                                   step="0.1"
                                                   min="0"
                                                   wire:model.live="wasteWeights.{{ $wasteType->id }}"
                                                   @if(!$selectedWasteTypes[$wasteType->id]) disabled @endif>
                                            @error('wasteWeights.'.$wasteType->id)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   class="form-control" 
                                                   value="Rp{{ number_format($wasteType->price_per_kg, 0, ',', '.') }}" 
                                                   disabled>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   class="form-control" 
                                                   value="Rp{{ number_format($this->getWasteTotal($wasteType->id), 0, ',', '.') }}" 
                                                   disabled>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            Tidak ada jenis sampah yang tersedia
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($wasteTypes->count() > 0)
                        <div class="d-flex justify-content-between align-items-center gap-3 mt-5">
                            <div class="col d-flex gap-5">
                                <div>
                                    <label for="" class="form-label text-secondary">Total Pendapatan</label>
                                    <h5>Rp{{ number_format($totalAmount, 0, ',', '.') }}</h5>
                                </div>
                                <div>
                                    <label for="" class="form-label text-secondary">Total Berat</label>
                                    <h5>{{ number_format($totalWeight, 1) }} Kg</h5>
                                </div>
                            </div>
                            <div class="col text-end">
                                <button type="submit" 
                                        class="btn btn-success fw-bold"
                                        @if($totalAmount <= 0) disabled @endif>
                                    <span wire:loading.remove wire:target="submitDeposit">
                                        Setor Sekarang
                                    </span>
                                    <span wire:loading wire:target="submitDeposit">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        Memproses...
                                    </span>
                                </button>
                                @if($totalAmount <= 0)
                                    <small class="text-muted d-block mt-1">Pilih dan isi berat sampah terlebih dahulu</small>
                                @endif
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
