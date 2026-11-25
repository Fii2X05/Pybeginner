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
        Schema::table('test_cases', function (Blueprint $table): void {
            if (! Schema::hasColumn('test_cases', 'input_data')) {
                $table->jsonb('input_data')->nullable()->after('input');
            }
        });

        Schema::table('submissions', function (Blueprint $table): void {
            if (! Schema::hasColumn('submissions', 'judge0_status_id')) {
                $table->unsignedSmallInteger('judge0_status_id')->nullable()->after('execution_time_ms');
            }
            if (! Schema::hasColumn('submissions', 'judge0_status_description')) {
                $table->string('judge0_status_description', 64)->nullable()->after('judge0_status_id');
            }
            if (! Schema::hasColumn('submissions', 'user_stdout')) {
                $table->text('user_stdout')->nullable()->after('judge0_status_description');
            }
            if (! Schema::hasColumn('submissions', 'error_message')) {
                $table->text('error_message')->nullable()->after('user_stdout');
            }
            if (! Schema::hasColumn('submissions', 'details')) {
                $table->jsonb('details')->nullable()->after('error_message');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table): void {
            $columns = array_values(array_filter([
                Schema::hasColumn('submissions', 'judge0_status_id') ? 'judge0_status_id' : null,
                Schema::hasColumn('submissions', 'judge0_status_description') ? 'judge0_status_description' : null,
                Schema::hasColumn('submissions', 'user_stdout') ? 'user_stdout' : null,
                Schema::hasColumn('submissions', 'error_message') ? 'error_message' : null,
                Schema::hasColumn('submissions', 'details') ? 'details' : null,
            ]));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('test_cases', function (Blueprint $table): void {
            if (Schema::hasColumn('test_cases', 'input_data')) {
                $table->dropColumn('input_data');
            }
        });
    }
};
