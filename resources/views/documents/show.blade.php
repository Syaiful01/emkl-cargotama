@extends('layouts.app')

@section('content')
<div class="container-fluid animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold">Detail Dokumen</h4>
            <p class="text-muted">Informasi lengkap dan riwayat versi dokumen.</p>
        </div>
        <div>
            <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
            <a href="{{ route('documents.preview', $document->id) }}" target="_blank" class="btn btn-primary">
                <i class="bi bi-eye me-2"></i> Pratinjau File
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Informasi Dokumen</h6>
                <div class="mb-3">
                    <label class="small text-muted d-block">Judul Dokumen</label>
                    <span class="fw-bold">{{ $document->title }}</span>
                </div>
                <div class="mb-3">
                    <label class="small text-muted d-block">Tipe</label>
                    <span class="badge bg-light text-dark px-3">{{ $document->type }}</span>
                </div>
                <div class="mb-3">
                    <label class="small text-muted d-block">Nomor Pekerjaan</label>
                    <span class="fw-bold text-primary">{{ $document->shipment->job_number }}</span>
                </div>
                <div class="mb-3">
                    <label class="small text-muted d-block">Diunggah Oleh</label>
                    <span>{{ $document->user->name ?? 'Sistem' }}</span>
                </div>
                <div class="mb-0">
                    <label class="small text-muted d-block">Tanggal Unggah</label>
                    <span>{{ $document->created_at->translatedFormat('d F Y, H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Riwayat Versi</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Versi</th>
                                <th>Tanggal Perubahan</th>
                                <th>Petugas</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($document->versions->sortByDesc('version') as $version)
                            <tr>
                                <td><span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3">v{{ $version->version }}</span></td>
                                <td>{{ $version->created_at->translatedFormat('d M Y, H:i') }}</td>
                                <td>{{ $version->user->name ?? 'Sistem' }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-dark"><i class="bi bi-download me-1"></i> Unduh</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
