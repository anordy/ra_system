<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrTemporaryTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_temporary_transports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mvr_registration_id');
            $table->unsignedBigInteger('taxpayer_id');
            $table->dateTime('date_of_travel');
            $table->dateTime('date_of_return');
            $table->dateTime('actual_return_date')->nullable();
            $table->text('reasons');
            $table->string('police_evidence');
            $table->string('release_letter');
            $table->dateTime('approved_on')->nullable();
            $table->text('marking')->nullable();
            $table->string('status')->default(\App\Enum\MvrTemporaryTransportStatus::PENDING);
            $table->softDeletes();
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
        Schema::dropIfExists('mvr_temporary_transports');
    }
}
