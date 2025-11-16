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
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwal_mengajar')->onDelete('cascade');
            $table->string('qr_data')->unique();
            $table->string('qr_image_path')->nullable();
            $table->dateTime('expired_at');
            $table->boolean('is_used')->default(false);
            $table->dateTime('used_at')->nullable();
            $table->foreignId('used_by_ketua_kelas')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('qr_data');
            $table->index('expired_at');
            $table->index(['guru_id', 'jadwal_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
