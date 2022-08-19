<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrDeRegistrationRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_de_registration_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_taxpayer_id');
            $table->unsignedBigInteger('mvr_motor_vehicle_id');
            $table->unsignedBigInteger('mvr_de_registration_reason_id');
            $table->string('description',1000);
            $table->date('date_received')->useCurrent();
            $table->string('inspection_report_path',100)->nullable();
            $table->string('certificate_path',100)->nullable();
            $table->date('certificate_date')->nullable();
            $table->unsignedBigInteger('mvr_request_status_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('mvr_request_status_id')->references('id')->on('mvr_request_status');
            $table->foreign('mvr_motor_vehicle_id')->references('id')->on('mvr_motor_vehicles');
            $table->foreign('mvr_de_registration_reason_id','mvr_de_reg_requests_reg_reason_id_foreign')->references('id')->on('mvr_de_registration_reasons');
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
        Schema::dropIfExists('mvr_de_registration_requests');
    }
}
