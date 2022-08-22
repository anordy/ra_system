<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrOwnershipTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_ownership_transfer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mvr_motor_vehicle_id');
            $table->unsignedBigInteger('mvr_ownership_transfer_reason_id');
            $table->string('transfer_reason',255)->nullable();
            $table->unsignedBigInteger('mvr_transfer_category_id');
            $table->decimal('market_value',10)->nullable();
            $table->date('sale_date');
            $table->date('delivered_date')->comment("Date vehicle delivered to new owner");
            $table->date('application_date');
            $table->string('certificate_path',255)->nullable();
            $table->string('agreement_contract_path',255)->nullable();
            $table->unsignedBigInteger('agent_taxpayer_id');
            $table->unsignedBigInteger('owner_taxpayer_id');
            $table->unsignedBigInteger('mvr_request_status_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('mvr_request_status_id')->references('id')->on('mvr_request_status');
            $table->foreign('mvr_motor_vehicle_id')->references('id')->on('mvr_motor_vehicles');
            $table->foreign('mvr_ownership_transfer_reason_id')->references('id')->on('mvr_ownership_transfer_reasons');
            $table->foreign('agent_taxpayer_id')->references('id')->on('taxpayers');
            $table->foreign('owner_taxpayer_id')->references('id')->on('taxpayers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mvr_ownership_transfer');
    }
}
