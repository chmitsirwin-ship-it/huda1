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
        Schema::create('quran_verses', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('surah_number');
            $table->unsignedSmallInteger('verse_number');
            $table->string('surah_name');
            $table->text('arabic_text');
            $table->json('translation');
            $table->json('tafsir')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index(['surah_number', 'verse_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quran_verses');
    }
};
