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
        Schema::create('notifikasi_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');

            // Channel preferences
            $table->boolean('notif_web')->default(true);
            $table->boolean('notif_email')->default(false);
            $table->boolean('notif_whatsapp')->default(false);
            $table->boolean('notif_push')->default(true);

            // Event preferences
            $table->boolean('notif_jadwal_mengajar')->default(true);
            $table->boolean('notif_absensi_berhasil')->default(true);
            $table->boolean('notif_izin_cuti')->default(true);
            $table->boolean('notif_approval')->default(true);
            $table->boolean('notif_peringatan')->default(true);
            $table->boolean('notif_broadcast')->default(true);

            // Time preferences
            $table->time('quiet_time_start')->nullable()->comment('Jam mulai jangan ganggu');
            $table->time('quiet_time_end')->nullable()->comment('Jam selesai jangan ganggu');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi_preferences');
    }
};
