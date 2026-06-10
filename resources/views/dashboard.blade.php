@extends('layouts.app')

@section('content')
<div class="container-fluid animate-fade-in">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold">Resume Bisnis</h4>
            <p class="text-muted">Selamat datang kembali, berikut perkembangan hari ini.</p>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Total Pelanggan</span>
                    <i class="bi bi-people text-primary fs-4"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ number_format($totalCustomers) }}</h3>
                <div class="mt-2">
                    <span class="text-success small fw-bold"><i class="bi bi-arrow-up"></i> Baru</span>
                    <span class="text-muted small ms-1">Data riil</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Pengiriman Aktif</span>
                    <i class="bi bi-box-seam text-accent fs-4"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ number_format($activeShipments) }}</h3>
                <div class="mt-2">
                    <span class="text-success small fw-bold"><i class="bi bi-info-circle"></i> Berjalan</span>
                    <span class="text-muted small ms-1">Sedang diproses</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Pendapatan Bulanan</span>
                    <i class="bi bi-currency-dollar text-success fs-4"></i>
                </div>
                <h3 class="fw-bold mb-0">Rp{{ number_format($monthlyRevenue / 1000, 0, ',', '.') }}k</h3>
                <div class="mt-2">
                    <span class="text-primary small fw-bold"><i class="bi bi-calendar-check"></i> {{ now()->translatedFormat('F') }}</span>
                    <span class="text-muted small ms-1">Bulan ini</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Invoice Jatuh Tempo</span>
                    <i class="bi bi-exclamation-octagon text-danger fs-4"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ number_format($overdueInvoices) }}</h3>
                <div class="mt-2">
                    <span class="text-danger small fw-bold">Perlu Tindakan</span>
                    <span class="text-muted small ms-1">Segera</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-md-8">
            <div class="card p-4">
                <h6 class="fw-bold mb-4">Analisis Pendapatan</h6>
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <h6 class="fw-bold mb-4">Status Pengiriman</h6>
                <canvas id="statusChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card p-4">
                <h6 class="fw-bold mb-4">Aktivitas Terbaru</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Aktivitas</th>
                                <th>Nomor Pekerjaan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $activity)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded me-3 text-primary">
                                            <i class="bi bi-info-circle"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $activity->description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="fw-bold text-primary">{{ $activity->job_number }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($activity->created_at)->translatedFormat('d M Y, H:i') }}</td>
                                <td>
                                    @php
                                        $statusClass = match($activity->status) {
                                            'draft' => 'bg-secondary',
                                            'booked' => 'bg-info',
                                            'loading' => 'bg-warning',
                                            'in transit' => 'bg-primary',
                                            'completed' => 'bg-success',
                                            default => 'bg-secondary'
                                        };
                                        $statusLabel = match($activity->status) {
                                            'draft' => 'Draf',
                                            'booked' => 'Dipesan',
                                            'loading' => 'Muat',
                                            'in transit' => 'Dalam Perjalanan',
                                            'completed' => 'Selesai',
                                            default => $activity->status
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $statusClass) }} px-3">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td>{{ $activity->user_name }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada aktivitas tercatat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Revenue Chart
    const revCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Pendapatan',
                data: [30000, 35000, 32000, 40000, 45000, 52400],
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                x: { grid: { display: false } }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Dalam Perjalanan', 'Muat', 'Tiba', 'Selesai'],
            datasets: [{
                data: [15, 10, 5, 20],
                backgroundColor: ['#2563EB', '#06B6D4', '#F59E0B', '#10B981'],
                borderWidth: 0
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } },
            cutout: '70%'
        }
    });
</script>
@endpush
