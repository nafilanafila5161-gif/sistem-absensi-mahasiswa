@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Dashboard Utama Admin</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card bg-white shadow-sm p-4">
                <h5>Total Mahasiswa</h5>
                <h2 class="display-4">{{ $stats['total_mahasiswa'] }}</h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-white shadow-sm p-4">
                <h5>Total Dosen</h5>
                <h2 class="display-4">{{ $stats['total_dosen'] }}</h2>
            </div>
        </div>
    </div>
</div>
@endsection