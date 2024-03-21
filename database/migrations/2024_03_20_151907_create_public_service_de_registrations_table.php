<?php

use App\Enum\PublicService\DeRegistrationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicServiceDeRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_service_de_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('public_service_motor_id');
            $table->date('de_registration_date');
            $table->text('reason');
            $table->enum('status', DeRegistrationStatus::getConstants());
            $table->unsignedBigInteger('created_by');
            $table->text('marking')->nullable();
            $table->dateTime('approved_on')->nullable();
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
        Schema::dropIfExists('public_service_de_registrations');
    }
}
