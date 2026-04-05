<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('khutbas', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->json('content')->nullable()->after('summary');
            $table->string('featured_image')->nullable()->after('video_url');
        });
    }

    public function down(): void
    {
        Schema::table('khutbas', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'content', 'featured_image']);
        });
    }
};
