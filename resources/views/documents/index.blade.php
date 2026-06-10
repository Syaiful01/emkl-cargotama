@extends('layouts.app')

@section('content')
<div class="container-fluid animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold">Arsip Digital</h4>
            <p class="text-muted">Kelola semua dokumen ekspor-impor dalam satu tempat.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
            <i class="bi bi-upload me-2"></i> Unggah Dokumen
        </button>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Judul Dokumen</th>
                        <th>Tipe</th>
                        <th>Nomor Pekerjaan</th>
                        <th>Diunggah Oleh</th>
                        <th>Tanggal</th>
                        <th>Versi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-pdf text-danger fs-4 me-3"></i>
                                <span class="fw-bold">{{ $doc->title }}</span>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark px-3">{{ $doc->type }}</span></td>
                        <td>{{ $doc->shipment->job_number }}</td>
                        <td>{{ $doc->user->name ?? 'Sistem' }}</td>
                        <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                        <td><span class="badge rounded-pill bg-primary bg-opacity-10 text-primary">v{{ $doc->version }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('documents.show', $doc->id) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-eye"></i></a>
                            <button class="btn btn-sm btn-outline-dark"><i class="bi bi-download"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-folder-x text-muted fs-1 d-block mb-3"></i>
                            <span class="text-muted">Tidak ada dokumen ditemukan di arsip.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $documents->links() }}
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Unggah Dokumen Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pengiriman / Nomor Pekerjaan</label>
                        <select name="shipment_id" class="form-select" required>
                            <option value="">Pilih Pengiriman</option>
                            @foreach($shipments as $shipment)
                            <option value="{{ $shipment->id }}">{{ $shipment->job_number }} - {{ $shipment->vessel_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tipe Dokumen</label>
                        <select name="type" class="form-select" required>
                            <option value="Bill of Lading">Bill of Lading</option>
                            <option value="Packing List">Packing List</option>
                            <option value="Commercial Invoice">Commercial Invoice</option>
                            <option value="Certificate of Origin">Certificate of Origin</option>
                            <option value="Other">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul Dokumen</label>
                        <input type="text" name="title" class="form-control" placeholder="contoh: Master BL 12345" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">File (PDF, JPG, PNG)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Unggah Dokumen</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
