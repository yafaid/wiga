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
        Schema::create('presensi_harian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('kelas_id'); 
            $table->date('tanggal');
            $table->string('keterangan');
            $table->timestamps();
    
            $table->foreign('siswa_id')
                ->references('id')
                ->on('siswa')
                ->onDelete('cascade');
    
            $table->foreign('kelas_id') 
                ->references('id')
                ->on('kelas')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_harian');
    }
};
