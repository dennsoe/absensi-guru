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
        Schema::create('backup_history', function (Blueprint $table) {
            $table->id();
            $table->enum('backup_type', ['manual', 'scheduled', 'automatic'])->default('automatic');
            $table->string('filename', 255);
            $table->string('file_path');
            $table->bigInteger('file_size')->comment('in bytes');
            $table->enum('status', ['started', 'completed', 'failed'])->default('started');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->text('error_message')->nullable();
            $table->json('backup_info')->nullable()->comment('Tables, rows count, etc');
            $table->string('storage_location', 100)->default('local')->comment('local, s3, google_drive');
            $table->foreignId('triggered_by')->nullable()->constrained('users');
            $table->boolean('is_downloadable')->default(true);
            $table->date('delete_after')->nullable()->comment('Auto delete date');
            $table->timestamps();

            $table->index('status');
            $table->index('started_at');
            $table->index('delete_after');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_history');
    }
};
