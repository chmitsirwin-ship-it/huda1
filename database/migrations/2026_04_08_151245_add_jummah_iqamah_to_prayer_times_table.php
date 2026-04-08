<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('prayer_times', function (Blueprint $table) {
            $table->time('jummah_iqamah')->nullable()->after('jummah_khutba_time');
        });
    }

    public function down(): void
    {
        Schema::table('prayer_times', function (Blueprint $table) {
            $table->dropColumn('jummah_iqamah');
        });
    }
};
