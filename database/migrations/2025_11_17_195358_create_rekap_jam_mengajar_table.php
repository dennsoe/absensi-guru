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
        Schema::create('rekap_jam_mengajar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
            $table->integer('tahun');
            $table->integer('bulan')->comment('1-12');

            // Statistik mengajar
            $table->integer('total_jadwal')->default(0)->comment('Total jadwal di bulan ini');
            $table->integer('total_hadir')->default(0);
            $table->integer('total_izin')->default(0);
            $table->integer('total_sakit')->default(0);
            $table->integer('total_alfa')->default(0);
            $table->integer('total_terlambat')->default(0);

            // Jam mengajar
            $table->decimal('jam_mengajar_seharusnya', 8, 2)->default(0)->comment('Total jam dari jadwal');
            $table->decimal('jam_mengajar_terealisasi', 8, 2)->default(0)->comment('Jam aktual mengajar');
            $table->decimal('persentase_kehadiran', 5, 2)->default(0)->comment('Percentage');

            // Performance
            $table->integer('total_tepat_waktu')->default(0);
            $table->decimal('rata_rata_keterlambatan', 8, 2)->default(0)->comment('dalam menit');

            // Status
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->timestamp('finalized_at')->nullable();
            $table->foreignId('finalized_by')->nullable()->constrained('users');

            $table->timestamps();

            $table->unique(['guru_id', 'tahun', 'bulan']);
            $table->index(['tahun', 'bulan']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_jam_mengajar');
    }
};
