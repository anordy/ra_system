<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStampDutyServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stamp_duty_services', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description');
            $table->enum('rate_type', ['fixed', 'percent'])->default('fixed');
            $table->decimal('rate', 30, 3);
            $table->decimal('max', 30, 3)->nullable();
            $table->decimal('min', 30, 3)->nullable();
            $table->string('liable_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stamp_duty_services');
    }
}
