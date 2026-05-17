<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('sesi_absensi', function (Blueprint $table) {
        $table->integer('radius')->default(50)->after('longitude');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sesi_absensi', function (Blueprint $table) {
            //
        });
    }
};
