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
        Schema::create('dokumen_wajib', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_id')->constrained()->onDelete('cascade');
            $table->enum('jenis_dokumen', [
                'ktp_pemohon',
                'kk_pemohon', 
                'surat_pernyataan_kaling',
                'ktp_saksi1',
                'ktp_saksi2',
                'foto_tempat_usaha',
                'surat_rs',
                'foto_makam',
                'ktp_ayah',
                'ktp_ibu'
            ]);
            $table->string('nama_file');
            $table->string('file_path');
            $table->integer('file_size');
            $table->string('mime_type');
            $table->boolean('is_required')->default(true);
            $table->timestamps();
            
            $table->unique(['permohonan_id', 'jenis_dokumen']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_wajib');
    }
};