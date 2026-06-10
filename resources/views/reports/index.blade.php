@extends('layouts.app')

@section('content')
<div class="container-fluid animate-fade-in">
    <h4 class="fw-bold mb-4">Laporan Bisnis</h4>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card p-4 text-center">
                <i class="bi bi-file-earmark-bar-graph text-primary fs-2 mb-2"></i>
                <h6 class="fw-bold">Laporan Pengiriman</h6>
                <button class="btn btn-sm btn-outline-primary mt-2">Buat Laporan</button>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 text-center">
                <i class="bi bi-file-earmark-medical text-success fs-2 mb-2"></i>
                <h6 class="fw-bold">Laporan Pendapatan</h6>
                <button class="btn btn-sm btn-outline-success mt-2">Buat Laporan</button>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 text-center">
                <i class="bi bi-file-earmark-text text-warning fs-2 mb-2"></i>
                <h6 class="fw-bold">Laporan Piutang</h6>
                <button class="btn btn-sm btn-outline-warning mt-2">Buat Laporan</button>
            </div>
        </div>
    </div>
</div>
@endsection
