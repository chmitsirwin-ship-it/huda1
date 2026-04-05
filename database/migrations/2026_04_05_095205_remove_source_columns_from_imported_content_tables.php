<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'source_type')) {
                $table->dropUnique(['source_type', 'source_id']);
                $table->dropColumn('source_type');
            }

            if (Schema::hasColumn('news', 'source_id')) {
                $table->dropColumn('source_id');
            }
        });

        Schema::table('news_categories', function (Blueprint $table) {
            if (Schema::hasColumn('news_categories', 'source_type')) {
                $table->dropUnique(['source_type', 'source_id']);
                $table->dropColumn('source_type');
            }

            if (Schema::hasColumn('news_categories', 'source_id')) {
                $table->dropColumn('source_id');
            }
        });

        Schema::table('khutbas', function (Blueprint $table) {
            if (Schema::hasColumn('khutbas', 'source_type')) {
                $table->dropUnique(['source_type', 'source_id']);
                $table->dropColumn('source_type');
            }

            if (Schema::hasColumn('khutbas', 'source_id')) {
                $table->dropColumn('source_id');
            }
        });

        Schema::table('khutba_categories', function (Blueprint $table) {
            if (Schema::hasColumn('khutba_categories', 'source_type')) {
                $table->dropUnique(['source_type', 'source_id']);
                $table->dropColumn('source_type');
            }

            if (Schema::hasColumn('khutba_categories', 'source_id')) {
                $table->dropColumn('source_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
        });

        Schema::table('news_categories', function (Blueprint $table) {
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
        });

        Schema::table('khutbas', function (Blueprint $table) {
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
        });

        Schema::table('khutba_categories', function (Blueprint $table) {
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
        });
    }
};
