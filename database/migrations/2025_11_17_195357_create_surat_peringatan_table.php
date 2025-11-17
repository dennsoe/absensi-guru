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
        Schema::create('surat_peringatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
            $table->enum('jenis', ['SP1', 'SP2', 'SP3']);
            $table->date('tanggal_surat');
            $table->string('nomor_surat', 100)->unique();
            $table->text('alasan');
            $table->integer('jumlah_pelanggaran');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->string('file_pdf')->nullable();
            $table->enum('status', ['draft', 'terbit', 'diterima'])->default('draft');
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users');
            $table->timestamp('tanggal_diterima')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['guru_id', 'jenis']);
            $table->index('tanggal_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_peringatan');
    }
};
