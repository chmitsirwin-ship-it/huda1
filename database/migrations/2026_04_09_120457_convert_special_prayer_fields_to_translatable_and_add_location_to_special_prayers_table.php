<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $locale = config('app.fallback_locale', config('app.locale', 'en'));

        Schema::table('special_prayers', function (Blueprint $table) {
            $table->json('name_translations')->nullable()->after('id');
            $table->json('group_translations')->nullable()->after('name_translations');
            $table->json('description_translations')->nullable()->after('end_time');
            $table->json('location')->nullable()->after('description_translations');
        });

        DB::table('special_prayers')
            ->orderBy('id')
            ->get()
            ->each(function (object $specialPrayer) use ($locale): void {
                DB::table('special_prayers')
                    ->where('id', $specialPrayer->id)
                    ->update([
                        'name_translations' => json_encode([$locale => $specialPrayer->name], JSON_UNESCAPED_UNICODE),
                        'group_translations' => filled($specialPrayer->group)
                            ? json_encode([$locale => $specialPrayer->group], JSON_UNESCAPED_UNICODE)
                            : null,
                        'description_translations' => filled($specialPrayer->description)
                            ? json_encode([$locale => $specialPrayer->description], JSON_UNESCAPED_UNICODE)
                            : null,
                    ]);
            });

        Schema::table('special_prayers', function (Blueprint $table) {
            $table->dropColumn(['name', 'group', 'description']);
        });

        Schema::table('special_prayers', function (Blueprint $table) {
            $table->renameColumn('name_translations', 'name');
            $table->renameColumn('group_translations', 'group');
            $table->renameColumn('description_translations', 'description');
        });
    }

    public function down(): void
    {
        $locale = config('app.fallback_locale', config('app.locale', 'en'));

        Schema::table('special_prayers', function (Blueprint $table) {
            $table->string('name_text')->nullable()->after('id');
            $table->string('group_text')->nullable()->after('name_text');
            $table->text('description_text')->nullable()->after('end_time');
        });

        DB::table('special_prayers')
            ->orderBy('id')
            ->get()
            ->each(function (object $specialPrayer) use ($locale): void {
                $name = json_decode((string) $specialPrayer->name, true);
                $group = $specialPrayer->group ? json_decode((string) $specialPrayer->group, true) : null;
                $description = $specialPrayer->description ? json_decode((string) $specialPrayer->description, true) : null;

                DB::table('special_prayers')
                    ->where('id', $specialPrayer->id)
                    ->update([
                        'name_text' => $name[$locale] ?? reset($name) ?: null,
                        'group_text' => is_array($group) ? ($group[$locale] ?? reset($group) ?: null) : null,
                        'description_text' => is_array($description) ? ($description[$locale] ?? reset($description) ?: null) : null,
                    ]);
            });

        Schema::table('special_prayers', function (Blueprint $table) {
            $table->dropColumn(['name', 'group', 'description', 'location']);
        });

        Schema::table('special_prayers', function (Blueprint $table) {
            $table->renameColumn('name_text', 'name');
            $table->renameColumn('group_text', 'group');
            $table->renameColumn('description_text', 'description');
        });
    }
};
