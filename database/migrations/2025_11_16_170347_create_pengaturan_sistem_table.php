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
        Schema::create('pengaturan_sistem', function (Blueprint $table) {
            $table->id();
            $table->string('kategori', 50);
            $table->string('key', 100)->unique();
            $table->text('value');
            $table->enum('tipe_data', ['string', 'number', 'boolean', 'json', 'array'])->default('string');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();

            $table->index('kategori');
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sistem');
    }
};
