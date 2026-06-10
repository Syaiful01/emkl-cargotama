@extends('layouts.app')

@section('content')
<div class="container-fluid animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Manajemen Pengguna</h4>
        <button class="btn btn-primary btn-sm">Tambah Pengguna</button>
    </div>
    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Status</th>
                        <th>Login Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Super Admin</td>
                        <td>admin@emkl.com</td>
                        <td><span class="badge bg-primary">Administrator</span></td>
                        <td><span class="badge bg-success bg-opacity-10 text-success">Aktif</span></td>
                        <td>{{ now()->format('d M Y, H:i') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
