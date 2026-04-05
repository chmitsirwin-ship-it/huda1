<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khutba_category_khutba', function (Blueprint $table) {
            $table->foreignId('khutba_id')->constrained('khutbas')->cascadeOnDelete();
            $table->foreignId('khutba_category_id')->constrained('khutba_categories')->cascadeOnDelete();

            $table->primary(['khutba_id', 'khutba_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khutba_category_khutba');
    }
};
