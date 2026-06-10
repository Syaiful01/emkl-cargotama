@extends('layouts.app')

@section('content')
<div class="container-fluid animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold">Manajemen Pelanggan</h4>
            <p class="text-muted">Kelola basis data klien dan kontak person Anda.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
            <i class="bi bi-plus-lg me-2"></i> Tambah Pelanggan
        </button>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Perusahaan</th>
                        <th>PIC</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td class="fw-bold text-primary">{{ $customer->code }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->pic }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>
                            @if($customer->status == 'active')
                                <span class="badge bg-success bg-opacity-10 text-success px-3">Aktif</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-people text-muted fs-1 d-block mb-3"></i>
                            <span class="text-muted">Tidak ada data pelanggan. Mulai dengan menambah pelanggan baru.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Pelanggan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kode Pelanggan</label>
                            <input type="text" name="code" class="form-control" placeholder="misal: CUST-001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Perusahaan</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Legal Perusahaan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama PIC</label>
                            <input type="text" name="pic" class="form-control" placeholder="Orang yang Bisa Dihubungi">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control" placeholder="+62...">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Alamat Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@perusahaan.com">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Alamat</label>
                            <textarea name="address" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Pelanggan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
