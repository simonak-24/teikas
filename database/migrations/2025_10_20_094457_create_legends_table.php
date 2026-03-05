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
            $table->string('identifier', 9)->unique();
            $table->string('metadata');
            $table->string('title_lv', 100);
            $table->string('title_de', 100);
            $table->text('text_lv');
            $table->text('text_de');
            $table->string('chapter_lv', 100);
            $table->string('chapter_de', 100);
            $table->tinyInteger('volume');
            $table->text('comments')->nullable();
            $table->foreignId('collector_id')->nullable()->default(1);
            $table->foreignId('narrator_id')->nullable()->default(1);
            $table->foreignId('place_id')->nullable()->default(1);
            $table->string('external_identifier', 7)->nullable();
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
