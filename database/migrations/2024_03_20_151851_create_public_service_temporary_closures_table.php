<?php

use App\Enum\PublicService\TemporaryClosureStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicServiceTemporaryClosuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_service_temporary_closures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('public_service_motor_id');
            $table->date('closing_date');
            $table->date('opening_date');
            $table->date('re_opening_date')->nullable();
            $table->text('reason');
            $table->enum('status', TemporaryClosureStatus::getConstants());
            $table->unsignedBigInteger('created_by');
            $table->text('marking')->nullable();
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
        Schema::dropIfExists('public_service_temporary_closures');
    }
}
