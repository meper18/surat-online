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
        Schema::create('surat_mandahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_id')->constrained()->onDelete('cascade');
            $table->text('alamat_mandah');
            $table->string('kelurahan_mandah');
            $table->string('kecamatan_mandah');
            $table->string('kabupaten_mandah');
            $table->string('provinsi_mandah');
            $table->integer('jumlah_keluarga_ikut');
            $table->string('nama_pengikut1')->nullable();
            $table->string('jenis_kelamin_pengikut1')->nullable();
            $table->string('hubungan_keluarga_pengikut1')->nullable();
            $table->string('nama_pengikut2')->nullable();
            $table->string('jenis_kelamin_pengikut2')->nullable();
            $table->string('hubungan_keluarga_pengikut2')->nullable();
            $table->string('nama_pengikut3')->nullable();
            $table->string('jenis_kelamin_pengikut3')->nullable();
            $table->string('hubungan_keluarga_pengikut3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_mandahs');
    }
};