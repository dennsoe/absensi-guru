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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwal_mengajar')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->enum('status_kehadiran', ['hadir', 'terlambat', 'izin', 'sakit', 'alpha', 'dinas_luar', 'cuti'])->default('alpha');
            $table->enum('metode_absensi', ['qr_code', 'selfie', 'manual']);
            $table->string('foto_selfie')->nullable();
            $table->string('qr_code_data')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('validasi_gps')->default(false);
            $table->integer('jarak_dari_sekolah')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('file_pendukung')->nullable();
            $table->boolean('validasi_ketua_kelas')->default(false);
            $table->foreignId('ketua_kelas_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('waktu_validasi_ketua')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();

            $table->index(['guru_id', 'tanggal']);
            $table->index(['jadwal_id', 'tanggal']);
            $table->index('status_kehadiran');
            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
