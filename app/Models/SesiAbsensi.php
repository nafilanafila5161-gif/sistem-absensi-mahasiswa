<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiAbsensi extends Model
{
    use HasFactory;

    protected $table = 'sesi_absensi'; 

    // Tambahkan ini agar kolom lokasi bisa disimpan
    protected $fillable = [
        'kelas_id', 
        'qr_token', 
        'waktu_mulai', 
        'waktu_selesai', 
        'is_active',
        'latitude',      // Tambahkan ini
        'longitude',     // Tambahkan ini
        'radius_meter'   // Tambahkan ini
    ];

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke daftar hadir
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'sesi_id');
    }
}