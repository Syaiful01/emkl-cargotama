@extends('layouts.app')

@section('content')
<div class="container-fluid animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold">Invoice</h4>
            <p class="text-muted">Kelola penagihan, pembayaran, dan pembuatan invoice otomatis.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
            <i class="bi bi-plus-lg me-2"></i> Buat Invoice
        </button>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Nomor Invoice</th>
                        <th>Pelanggan</th>
                        <th>Nomor Pekerjaan</th>
                        <th>Tanggal</th>
                        <th>Jatuh Tempo</th>
                        <th>Total Keseluruhan</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    <tr>
                        <td class="fw-bold text-primary">{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->customer->name }}</td>
                        <td>{{ $invoice->shipment->job_number }}</td>
                        <td>{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                        <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                        <td class="fw-bold">Rp{{ number_format($invoice->grand_total, 2, ',', '.') }}</td>
                        <td>
                            @php
                                $statusClass = match($invoice->status) {
                                    'draft' => 'bg-secondary',
                                    'sent' => 'bg-primary',
                                    'paid' => 'bg-success',
                                    'overdue' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                                $statusLabel = match($invoice->status) {
                                    'draft' => 'Draf',
                                    'sent' => 'Terkirim',
                                    'paid' => 'Lunas',
                                    'overdue' => 'Jatuh Tempo',
                                    default => $invoice->status
                                };
                            @endphp
                            <span class="badge {{ $statusClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $statusClass) }} px-3 text-capitalize">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-sm btn-outline-danger me-1"><i class="bi bi-file-pdf"></i></a>
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-receipt text-muted fs-1 d-block mb-3"></i>
                            <span class="text-muted">Tidak ada data invoice ditemukan.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $invoices->links() }}
        </div>
    </div>
</div>

<!-- Create Invoice Modal -->
<div class="modal fade" id="createInvoiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Buat Invoice Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('invoices.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Pengiriman / Nomor Pekerjaan</label>
                            <select name="shipment_id" class="form-select" required>
                                <option value="">Pilih Pengiriman</option>
                                @foreach($shipments as $shipment)
                                <option value="{{ $shipment->id }}">{{ $shipment->job_number }} - {{ $shipment->customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tanggal Jatuh Tempo</label>
                            <input type="date" name="due_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tarif Pajak (misal: 0.11 untuk 11%)</label>
                            <input type="number" step="0.01" name="tax_rate" class="form-control" value="0.11">
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3">Item Invoice</h6>
                    <div id="invoice-items-container">
                        <div class="row g-2 mb-2 item-row">
                            <div class="col-md-5">
                                <input type="text" name="items[0][description]" class="form-control form-control-sm" placeholder="Deskripsi" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="items[0][quantity]" class="form-control form-control-sm qty-input" placeholder="Jumlah" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" step="0.01" name="items[0][unit_price]" class="form-control form-control-sm price-input" placeholder="Harga Satuan" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" step="0.01" name="items[0][total_price]" class="form-control form-control-sm total-input" placeholder="Total" readonly>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-item"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-item">
                        <i class="bi bi-plus-lg"></i> Tambah Item
                    </button>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Buat Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('invoice-items-container');
        const addButton = document.getElementById('add-item');
        let itemIndex = 1;

        addButton.addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className = 'row g-2 mb-2 item-row';
            newRow.innerHTML = `
                <div class="col-md-5">
                    <input type="text" name="items[${itemIndex}][description]" class="form-control form-control-sm" placeholder="Deskripsi" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control form-control-sm qty-input" placeholder="Jumlah" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="items[${itemIndex}][unit_price]" class="form-control form-control-sm price-input" placeholder="Harga Satuan" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="items[${itemIndex}][total_price]" class="form-control form-control-sm total-input" placeholder="Total" readonly>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-item"><i class="bi bi-trash"></i></button>
                </div>
            `;
            container.appendChild(newRow);
            itemIndex++;
        });

        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                const rows = container.querySelectorAll('.item-row');
                if (rows.length > 1) {
                    e.target.closest('.item-row').remove();
                }
            }
        });

        container.addEventListener('input', function(e) {
            if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
                const row = e.target.closest('.item-row');
                const qty = row.querySelector('.qty-input').value || 0;
                const price = row.querySelector('.price-input').value || 0;
                row.querySelector('.total-input').value = (qty * price).toFixed(2);
            }
        });
    });
</script>
@endpush
