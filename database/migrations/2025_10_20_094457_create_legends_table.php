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
        Schema::create('legends', function (Blueprint $table) {
            $table->id();
            $table->string('identifier', 9);
            $table->string('metadata');
            $table->string('title_lv');
            $table->string('title_de');
            $table->text('text_lv');
            $table->text('text_de');
            $table->string('chapter_lv');
            $table->string('chapter_de');
            $table->tinyInteger('volume');
            $table->text('comments')->nullable();
            $table->foreignId('collector_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('narrator_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('place_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legends');
    }
};
