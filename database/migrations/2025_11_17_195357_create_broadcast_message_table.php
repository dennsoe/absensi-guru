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
        Schema::create('broadcast_message', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->text('isi_pesan');
            $table->enum('tipe', ['info', 'peringatan', 'penting', 'pengumuman'])->default('info');
            $table->json('target_role')->comment('["admin","guru","ketua_kelas"]');
            $table->json('target_users')->nullable()->comment('Jika null = semua user');
            $table->enum('status', ['draft', 'scheduled', 'sent'])->default('draft');
            $table->timestamp('jadwal_kirim')->nullable();
            $table->timestamp('tanggal_kirim')->nullable();
            $table->foreignId('pengirim_id')->constrained('users');
            $table->integer('total_penerima')->default(0);
            $table->integer('total_dibaca')->default(0);
            $table->boolean('push_notification')->default(false);
            $table->boolean('email_notification')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('jadwal_kirim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broadcast_message');
    }
};
