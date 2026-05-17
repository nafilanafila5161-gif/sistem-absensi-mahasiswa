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
    Schema::create('kelas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
    $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah');
    $table->string('kode_kelas');
    $table->string('hari');
    $table->time('jam_mulai');
    $table->time('jam_selesai');
    $table->decimal('latitude', 10, 8); // Presisi koordinat GPS
    $table->decimal('longitude', 11, 8);
    $table->integer('radius_meter')->default(50); // Radius geofencing
    $table->timestamps();
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
