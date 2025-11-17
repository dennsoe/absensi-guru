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
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('service_name', 100)->comment('Email, WhatsApp, Google Maps, Firebase, dll');
            $table->string('provider', 100)->nullable()->comment('SendGrid, Twilio, Fonnte, dll');
            $table->text('api_key')->comment('Encrypted');
            $table->text('api_secret')->nullable()->comment('Encrypted');
            $table->string('api_url')->nullable();
            $table->json('config')->nullable()->comment('Additional configuration');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->integer('usage_count')->default(0);
            $table->integer('monthly_limit')->nullable()->comment('Request limit per bulan');
            $table->date('expired_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['service_name', 'provider']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};
