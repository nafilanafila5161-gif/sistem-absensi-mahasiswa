<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $fillable = [
        'sesi_id', 'mahasiswa_id', 'scan_at', 'latitude', 'longitude', 
        'jarak_meter', 'valid_qr', 'valid_waktu', 'valid_lokasi', 'status', 'device_info'
    ];

    // Di dalam class Absensi
public function user()
{
    // Menghubungkan kolom mahasiswa_id ke kolom id di tabel users
    return $this->belongsTo(User::class, 'mahasiswa_id', 'id');
}
    public function sesi() {
        return $this->belongsTo(SesiAbsensi::class);
    }

    public function mahasiswa() {
        return $this->belongsTo(Mahasiswa::class);
    }
}