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
        Schema::create('file_compressions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pid')->nullable();
            $table->boolean('active')->default(false);
            $table->string('file_name')->nullable(); //for overriding the relative path if desired
            $table->string('relative_path')->nullable();
            $table->string('file_type');
            $table->unsignedBigInteger('file_size_before')->nullable();
            $table->unsignedBigInteger('file_size_after')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedInteger('progress')->default(0);
            $table->string('eta')->nullable();
            $table->float('average_cpu_usage', 5, 2)->nullable();
            $table->float('average_memory_usage', 5, 2)->nullable();
            $table->string('total_elapsed_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_compressions');
    }
};
