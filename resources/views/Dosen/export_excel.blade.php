<table>
    <thead>
        <tr>
            <th colspan="6" style="font-size: 14px; font-weight: bold; text-align: center;">
                REKAPITULASI ABSENSI MAHASISWA PER SEMESTER
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; font-weight: bold;">
                Mata Kuliah: {{ $data->first()->sesi->kelas->nama_mk ?? '-' }} ({{ $data->first()->sesi->kelas->kode_kelas ?? '-' }})
            </th>
        </tr>
        <tr>
            <th colspan="6"></th>
        </tr>
        <tr style="background-color: #f2f2f2;">
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">No</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: left;">Nama Mahasiswa</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">NIM</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Tanggal Scan</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Jarak (Meter)</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Status Kehadiran</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $index => $item)
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000; text-align: left;">{{ $item->user->name ?? 'Tidak Diketahui' }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $item->user->nim ?? '-' }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $item->scan_at }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ number_format($item->jarak_meter, 1) }} m</td>
                <td style="border: 1px solid #000000; text-align: center; font-weight: bold;">{{ strtoupper($item->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>