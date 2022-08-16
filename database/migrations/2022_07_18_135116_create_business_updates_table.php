<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('taxpayer_id');
            $table->text('agent_contract')->nullable();
            $table->longText('old_values')->nullable();
            $table->longText('new_values');
            $table->enum('type', ['business_information', 'responsible_person'])->default('business_information');
            $table->enum('status', ['pending', 'approved', 'correction', 'rejected'])->default('pending');
            $table->string('marking')->nullable();
            $table->dateTime('approved_on')->nullable();
            $table->foreign('business_id')->references('id')->on('businesses');
            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
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
        Schema::dropIfExists('business_updates');
    }
}
