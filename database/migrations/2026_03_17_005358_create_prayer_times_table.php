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
        Schema::create('prayer_times', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->time('fajr_adhan')->nullable();
            $table->time('fajr_iqamah')->nullable();
            $table->time('sunrise')->nullable();
            $table->time('dhuhr_adhan')->nullable();
            $table->time('dhuhr_iqamah')->nullable();
            $table->time('asr_adhan')->nullable();
            $table->time('asr_iqamah')->nullable();
            $table->time('maghrib_adhan')->nullable();
            $table->time('maghrib_iqamah')->nullable();
            $table->time('isha_adhan')->nullable();
            $table->time('isha_iqamah')->nullable();
            $table->time('jummah_time')->nullable();
            $table->time('jummah_khutba_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_times');
    }
};
