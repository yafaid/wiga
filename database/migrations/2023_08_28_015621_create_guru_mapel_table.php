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
        Schema::create('guru_mapel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kodeguru'); // Foreign key dari tabel guru
            $table->unsignedBigInteger('kodemapel'); 
            // Definisi foreign key constraints
            $table->foreign('kodeguru')
            ->references('id')
            ->on('gurus');
            $table->foreign('kodemapel')
            ->references('id')
            ->on('mapels');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_mapel');
    }
};
