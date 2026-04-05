<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('news_categories', 'slug') || Schema::hasColumn('news_categories', 'description')) {
            Schema::table('news_categories', function (Blueprint $table) {
                if (Schema::hasColumn('news_categories', 'slug')) {
                    $table->dropUnique(['slug']);
                }

                $columns = array_values(array_filter([
                    Schema::hasColumn('news_categories', 'slug') ? 'slug' : null,
                    Schema::hasColumn('news_categories', 'description') ? 'description' : null,
                ]));

                if ($columns !== []) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasColumn('khutba_categories', 'slug') || Schema::hasColumn('khutba_categories', 'description')) {
            Schema::table('khutba_categories', function (Blueprint $table) {
                if (Schema::hasColumn('khutba_categories', 'slug')) {
                    $table->dropUnique(['slug']);
                }

                $columns = array_values(array_filter([
                    Schema::hasColumn('khutba_categories', 'slug') ? 'slug' : null,
                    Schema::hasColumn('khutba_categories', 'description') ? 'description' : null,
                ]));

                if ($columns !== []) {
                    $table->dropColumn($columns);
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('news_categories', function (Blueprint $table) {
            $table->json('description')->nullable()->after('name');
            $table->string('slug')->nullable()->after('description');
            $table->unique('slug');
        });

        Schema::table('khutba_categories', function (Blueprint $table) {
            $table->json('description')->nullable()->after('name');
            $table->string('slug')->nullable()->after('description');
            $table->unique('slug');
        });
    }
};
