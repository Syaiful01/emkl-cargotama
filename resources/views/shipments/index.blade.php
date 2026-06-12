@extends('layouts.app')

@section('content')
<div class="container-fluid animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold">Pelacakan Pengiriman</h4>
            <p class="text-muted">Pantau pengiriman aktif dan kemajuan logistik.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addShipmentModal">
            <i class="bi bi-plus-lg me-2"></i> Pengiriman Baru
        </button>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Nomor Pekerjaan</th>
                        <th>Pelanggan</th>
                        <th>Kapal / Voyage</th>
                        <th>POL / POD</th>
                        <th>ETD / ETA</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shipments as $shipment)
                    <tr>
                        <td class="fw-bold text-primary">{{ $shipment->job_number }}</td>
                        <td>{{ $shipment->customer->name }}</td>
                        <td>
                            <div class="small fw-bold">{{ $shipment->vessel_name }}</div>
                            <div class="text-muted small">{{ $shipment->voyage }}</div>
                        </td>
                        <td>
                            <div class="small">{{ $shipment->pol }}</div>
                            <i class="bi bi-arrow-right small text-muted"></i>
                            <div class="small">{{ $shipment->pod }}</div>
                        </td>
                        <td>
                            <div class="small">D: {{ $shipment->etd ? $shipment->etd->format('d/m/y') : '-' }}</div>
                            <div class="small">A: {{ $shipment->eta ? $shipment->eta->format('d/m/y') : '-' }}</div>
                        </td>
                        <td>
                            @php
                                $statusClass = match($shipment->status) {
                                    'draft' => 'bg-secondary',
                                    'booked' => 'bg-info',
                                    'loading' => 'bg-warning',
                                    'in transit' => 'bg-primary',
                                    'completed' => 'bg-success',
                                    default => 'bg-secondary'
                                };
                                $statusLabel = match($shipment->status) {
                                    'draft' => 'Draf',
                                    'booked' => 'Dipesan',
                                    'loading' => 'Muat',
                                    'in transit' => 'Dalam Perjalanan',
                                    'completed' => 'Selesai',
                                    default => $shipment->status
                                };
                            @endphp
                            <span class="badge {{ $statusClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $statusClass) }} px-3 text-capitalize">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-sm btn-outline-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-eye me-2 text-primary"></i> Detail</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    @if($shipment->status !== 'completed')
                                    <li>
                                        <form action="{{ route('shipments.update-status', $shipment->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <input type="hidden" name="description" value="Pengiriman telah sampai di tujuan.">
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-check-circle me-2 text-success"></i> Tandai Sudah Sampai
                                            </button>
                                        </form>
                                    </li>
                                    @else
                                    <li>
                                        <form action="{{ route('shipments.update-status', $shipment->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="in transit">
                                            <input type="hidden" name="description" value="Dikembalikan ke status dalam perjalanan.">
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-x-circle me-2 text-danger"></i> Tandai Belum Sampai
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-box-seam text-muted fs-1 d-block mb-3"></i>
                            <span class="text-muted">Tidak ada data pengiriman ditemukan.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $shipments->links() }}
        </div>
    </div>
</div>

<!-- Add Shipment Modal -->
<div class="modal fade" id="addShipmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Buat Pengiriman Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('shipments.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nomor Pekerjaan (Job Number)</label>
                            <input type="text" name="job_number" class="form-control" placeholder="JOB-2026-001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Pelanggan</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Kapal</label>
                            <input type="text" name="vessel_name" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Voyage</label>
                            <input type="text" name="voyage" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">POL (Pelabuhan Muat)</label>
                            <input type="text" name="pol" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">POD (Pelabuhan Bongkar)</label>
                            <input type="text" name="pod" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">ETD</label>
                            <input type="date" name="etd" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">ETA</label>
                            <input type="date" name="eta" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Buat Pengiriman</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
