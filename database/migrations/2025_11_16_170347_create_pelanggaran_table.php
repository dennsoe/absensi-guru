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
        Schema::create('pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
            $table->enum('jenis_pelanggaran', ['alpha', 'terlambat', 'tidak_absen_keluar', 'tidak_sesuai_jadwal']);
            $table->date('tanggal');
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwal_mengajar')->onDelete('set null');
            $table->text('keterangan')->nullable();
            $table->text('sanksi')->nullable();
            $table->integer('poin')->default(0);
            $table->enum('status', ['open', 'follow_up', 'resolved'])->default('open');
            $table->foreignId('ditangani_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['guru_id', 'tanggal']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggaran');
    }
};
