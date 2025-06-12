<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    @if (session()->has('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ $avatarPreview ?? auth()->user()->avatar_url }}" alt="Avatar"
                             class="rounded-circle shadow" width="80" height="80">
                        <div class="ms-4">
                            <h4 class="mb-1">{{ $editMode ? '' : auth()->user()->name }}</h4>
                            <span class="badge bg-primary">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</span>
                        </div>
                    </div>

                    @if ($editMode)
                        <form wire:submit.prevent="save" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-1">Nama</label>
                                <input type="text" class="form-control" wire:model.defer="name">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-1">No. HP</label>
                                <input type="text" class="form-control" wire:model.defer="phone">
                                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-1">Dusun</label>
                                <input type="text" class="form-control" wire:model.defer="dusun">
                                @error('dusun') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label class="form-label fw-semibold mb-1">RT</label>
                                    <input type="text" class="form-control" wire:model.defer="rt">
                                    @error('rt') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label fw-semibold mb-1">RW</label>
                                    <input type="text" class="form-control" wire:model.defer="rw">
                                    @error('rw') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="mb-3 mt-3">
                                <label class="form-label fw-semibold mb-1">Avatar</label>
                                <input type="file" class="form-control" wire:model="avatar" accept="image/*">
                                @error('avatar') <small class="text-danger">{{ $message }}</small> @enderror
                                @if ($avatarPreview)
                                    <div class="mt-2">
                                        <img src="{{ $avatarPreview }}" class="rounded-circle" width="60" height="60">
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-secondary btn-sm" wire:click="cancelEdit">Batal</button>
                                <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                            </div>
                        </form>
                    @else
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Email</label>
                            <div class="form-control-plaintext">{{ auth()->user()->email }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">No. HP</label>
                            <div class="form-control-plaintext">{{ auth()->user()->phone ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Alamat</label>
                            <div class="form-control-plaintext">
                                Dusun {{ auth()->user()->dusun ?? '-' }}, RT {{ auth()->user()->rt ?? '-' }}/RW {{ auth()->user()->rw ?? '-' }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Saldo</label>
                            <div class="form-control-plaintext text-success fs-5">Rp{{ auth()->user()->balance }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Status Akun</label>
                            <span class="badge {{ auth()->user()->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ auth()->user()->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button class="btn btn-outline-primary btn-sm" wire:click="enableEdit">Edit Profil</button>
                            <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>