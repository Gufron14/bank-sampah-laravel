<div class="mt-4">
    <h3 class="mb-3">Daftar Nasabah</h3>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr class="bg-light text-center text-uppercase">
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    <th>Alamat</th>
                    <th>Saldo</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nasabahs as $nasabah)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <img src="{{ $nasabah->avatar_url }}" alt="avatar" class="rounded-circle me-2" width="32" height="32">
                            {{ $nasabah->name }}
                        </td>
                        <td>{{ $nasabah->email }}</td>
                        <td>{{ $nasabah->phone }}</td>
                        <td>{{ $nasabah->dusun }}, RT. {{ $nasabah->rt }} RW.{{ $nasabah->rw }}</td>
                        <td>{{ $nasabah->formatted_balance }}</td>
                        <td>
                            @if($nasabah->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="" class="btn btn-warning btn-sm">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada nasabah.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>