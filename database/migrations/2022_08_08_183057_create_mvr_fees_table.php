<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->decimal('amount',10);
            $table->string('gfs_code',15);
            $table->unsignedBigInteger('mvr_fee_type_id');
            $table->unsignedBigInteger('mvr_registration_type_id')->nullable();
            $table->timestamps();

            $table->foreign('mvr_fee_type_id')->references('id')->on('mvr_fee_types');
            $table->foreign('mvr_registration_type_id')->references('id')->on('mvr_registration_types');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mvr_fees');
    }
}
