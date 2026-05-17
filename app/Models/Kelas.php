<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    
    protected $fillable = [
        'dosen_id', 
        'nama_mk', 
        'kode_kelas', 
        'hari', 
        'sks'
    ];

    public function dosen() {
        return $this->belongsTo(Dosen::class);
    }

    // HAPUS fungsi mataKuliah() karena tabelnya sudah tidak digunakan

    public function sesi() {
        return $this->hasMany(SesiAbsensi::class);
    }
}