
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Rekap Absensi Mahasiswa</h3>
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Nama Mahasiswa</th> <th>Mata Kuliah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekap as $data)
                    <tr>
                        <td>{{ $data->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $data->user->name }}</td> <td>{{ $data->matkul }}</td>
                        <td><span class="badge bg-success">Hadir</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection