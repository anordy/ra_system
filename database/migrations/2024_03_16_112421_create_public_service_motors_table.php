<?php

use App\Enum\PublicServiceMotorStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicServiceMotorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_service_motors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('taxpayer_id');
            $table->unsignedBigInteger('mvr_registration_id');
            $table->string('status')->default(PublicServiceMotorStatus::PENDING);
            $table->string('permission_document_path')->nullable();
            $table->string('road_license_path');
            $table->dateTime('approved_on')->nullable();
            $table->string('marking')->nullable();
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
        Schema::dropIfExists('public_service_motors');
    }
}
