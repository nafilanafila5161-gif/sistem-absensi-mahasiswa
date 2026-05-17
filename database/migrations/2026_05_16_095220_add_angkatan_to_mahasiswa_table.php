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
    Schema::table('mahasiswa', function (Blueprint $table) {
        // Ganti 'prodi' menjadi 'nama' atau 'nim' yang sudah ada di tabel Anda
        $table->year('angkatan')->after('nama')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            //
        });
    }
};
