<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kelas';

    // WAJIB MENULISKAN INI: Daftar kolom yang boleh diisi massal
    protected $fillable = [
        'dosen_id',
        'nama_mk',
        'kode_kelas',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'sks',
    ];

    // Relasi ke Dosen (jika diperlukan)
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }


    // HAPUS fungsi mataKuliah() karena tabelnya sudah tidak digunakan

    public function sesi() {
        return $this->hasMany(SesiAbsensi::class);
    }
}