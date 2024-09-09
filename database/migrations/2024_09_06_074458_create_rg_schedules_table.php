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
        Schema::create('rg_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rg_register_id');
            $table->unsignedBigInteger('cancelled_by_id')->nullable();
            $table->string('job_reference');
            $table->string('description')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('time');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rg_schedules');
    }
};
