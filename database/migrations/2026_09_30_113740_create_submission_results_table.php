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
        Schema::create('submission_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('submission_id')
                ->constrained('submissions')
                ->cascadeOnDelete();

            $table->foreignId('test_case_id')
                ->constrained('test_cases')
                ->cascadeOnDelete();

            $table->string('status');

            $table->text('actual_output')->nullable();
            $table->text('error_message')->nullable();

            $table->unsignedInteger('execution_time_ms')->nullable();

            $table->timestamps();

            $table->unique([
                'submission_id',
                'test_case_id',
            ]);

            $table->index([
                'submission_id',
                'status',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_results');
    }
};
