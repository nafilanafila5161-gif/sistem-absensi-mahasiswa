<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
   Schema::create('absensi', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sesi_id')->constrained('sesi_absensi');
    $table->foreignId('mahasiswa_id')->constrained('mahasiswa');
    $table->timestamp('scan_at');
    $table->decimal('latitude', 10, 8);
    $table->decimal('longitude', 11, 8);
    $table->integer('jarak_meter'); // Jarak mhs ke titik absen
    $table->enum('status', ['hadir', 'terlambat', 'izin', 'alpha']);
    $table->timestamps();
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
