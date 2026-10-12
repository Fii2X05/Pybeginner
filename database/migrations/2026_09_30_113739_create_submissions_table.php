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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('exercise_id')
                ->constrained('exercises')
                ->cascadeOnDelete();

            $table->text('source_code');

            $table->string('status')->default('pending');

            $table->decimal('score', 5, 2)->default(0);

            $table->unsignedInteger('passed_tests')->default(0);
            $table->unsignedInteger('total_tests')->default(0);

            $table->unsignedInteger('execution_time_ms')->nullable();

            $table->timestamp('submitted_at');

            $table->timestamps();

            $table->index([
                'user_id',
                'exercise_id',
                'submitted_at',
            ]);

            $table->index([
                'exercise_id',
                'score',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
