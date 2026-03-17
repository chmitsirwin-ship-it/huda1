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
        Schema::create('khutbas', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('topic')->nullable();
            $table->json('summary')->nullable();
            $table->json('speaker');
            $table->date('date');
            $table->string('audio_url')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khutbas');
    }
};
