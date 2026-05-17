<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiAbsensi extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk memberitahu Laravel nama tabel yang benar
    protected $table = 'sesi_absensi';

    // Pastikan juga kolom yang bisa diisi sudah didaftarkan
    protected $fillable = [
        'kelas_id', 
        'qr_token', 
        'waktu_mulai', 
        'waktu_selesai', 
        'is_active'
    ];
}