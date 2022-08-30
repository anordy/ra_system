<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrRegistrationChangeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_registration_change_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_taxpayer_id');
            $table->unsignedBigInteger('current_registration_id');
            $table->unsignedBigInteger('requested_registration_type_id');
            $table->unsignedBigInteger('mvr_plate_size_id');
            $table->string('custom_plate_number',20)->nullable();
            $table->date('date')->useCurrent();
            $table->unsignedBigInteger('mvr_request_status_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('current_registration_id','mvr_registration_change_requests_cur_reg')->references('id')->on('mvr_motor_vehicle_registration');
            $table->foreign('requested_registration_type_id','mvr_registration_change_requests_req_reg_type')->references('id')->on('mvr_registration_types');
            $table->foreign('mvr_request_status_id')->references('id')->on('mvr_request_status');
            $table->foreign('mvr_plate_size_id')->references('id')->on('mvr_plate_sizes');
            $table->foreign('agent_taxpayer_id')->references('id')->on('taxpayers');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mvr_registration_change_requests');
    }
}
