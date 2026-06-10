@extends('layouts.app')

@section('content')
<div class="container-fluid animate-fade-in">
    <h4 class="fw-bold mb-4">Pengaturan Sistem</h4>
    <div class="card p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label small fw-bold">Nama Perusahaan</label>
                <input type="text" class="form-control" value="EMKL Automation System">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold">Email Sistem</label>
                <input type="email" class="form-control" value="admin@emkl.com">
            </div>
            <div class="col-12">
                <button class="btn btn-primary px-4">Simpan Pengaturan</button>
            </div>
        </div>
    </div>
</div>
@endsection
