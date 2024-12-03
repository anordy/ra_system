<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNtrBusinessDeregistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ntr_business_deregistrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ntr_business_id');
            $table->string('reason');
            $table->string('marking')->nullable();
            $table->string('status');
            $table->timestamp('approved_on')->nullable();
            $table->timestamp('rejected_on')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ntr_business_deregistrations');
    }
}
