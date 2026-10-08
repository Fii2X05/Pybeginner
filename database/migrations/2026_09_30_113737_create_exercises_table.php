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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lesson_id')
                ->constrained('lessons')
                ->cascadeOnDelete();

            $table->string('title');
            $table->string('slug');

            $table->text('description');
            $table->text('instructions')->nullable();

            $table->text('starter_code')->nullable();

            $table->string('difficulty')->default('easy');

            $table->unsignedInteger('time_limit_ms')->default(10000);
            $table->unsignedInteger('memory_limit_mb')->default(128);

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(false);

            $table->timestamps();

            $table->unique(['lesson_id', 'slug']);
            $table->index([
                'lesson_id',
                'is_published',
                'sort_order',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
