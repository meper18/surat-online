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
        Schema::table('permohonans', function (Blueprint $table) {
            $table->text('keperluan')->after('jenis_surat_id');
            $table->string('dokumen_pendukung')->nullable()->after('keperluan');
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->after('catatan');
            $table->timestamp('tanggal_diproses')->nullable()->after('diproses_oleh');
            $table->timestamp('tanggal_selesai')->nullable()->after('tanggal_diproses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonans', function (Blueprint $table) {
            $table->dropColumn(['keperluan', 'dokumen_pendukung', 'diproses_oleh', 'tanggal_diproses', 'tanggal_selesai']);
        });
    }
};