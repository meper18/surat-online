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
            $table->enum('signature_type', ['digital', 'qr_code'])->nullable()->after('file_surat');
            $table->text('digital_signature')->nullable()->after('signature_type');
            $table->string('qr_code_data')->nullable()->after('digital_signature');
            $table->string('qr_code_image')->nullable()->after('qr_code_data');
            $table->timestamp('signed_at')->nullable()->after('qr_code_image');
            $table->unsignedBigInteger('signed_by')->nullable()->after('signed_at');
            
            $table->foreign('signed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonans', function (Blueprint $table) {
            $table->dropForeign(['signed_by']);
            $table->dropColumn([
                'signature_type',
                'digital_signature', 
                'qr_code_data',
                'qr_code_image',
                'signed_at',
                'signed_by'
            ]);
        });
    }
};
