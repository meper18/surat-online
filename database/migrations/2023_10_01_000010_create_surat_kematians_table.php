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
        Schema::create('surat_kematians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_id')->constrained()->onDelete('cascade');
            $table->string('hubungan_keluarga');
            $table->string('nama_meninggal');
            $table->string('tempat_lahir_meninggal');
            $table->date('tanggal_lahir_meninggal');
            $table->string('nik_meninggal');
            $table->string('nomor_kk_meninggal');
            $table->string('agama_meninggal');
            $table->text('alamat_meninggal');
            $table->string('hari_meninggal');
            $table->date('tanggal_meninggal');
            $table->time('waktu_meninggal');
            $table->string('tempat_meninggal');
            $table->string('penentu_kematian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_kematians');
    }
};