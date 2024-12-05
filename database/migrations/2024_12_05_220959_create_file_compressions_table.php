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
            $table->string('file_name');
            $table->unsignedBigInteger('file_size_before')->nullable();
            $table->unsignedBigInteger('file_size_after')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->unsignedInteger('progress')->default(0);
            $table->string('eta')->nullable();
            $table->string('file_type');
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
