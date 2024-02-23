<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrDeregistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_deregistrations', function (Blueprint $table) {
            $table->id();
            $table->string('mvr_registration_id');
            $table->string('marking')->nullable();
            $table->unsignedBigInteger('taxpayer_id');
            $table->unsignedBigInteger('mvr_de_registration_reason_id');
            $table->string('status')->default('pending');
            $table->string('clearance_evidence')->nullable();
            $table->string('police_evidence')->nullable();
            $table->string('zic_evidence')->nullable();
            $table->string('description')->nullable();
            $table->string('payment_status')->nullable();
            $table->dateTime('deregistered_at')->nullable();
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
        Schema::dropIfExists('mvr_deregistrations');
    }
}
