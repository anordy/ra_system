<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessDirectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_directors', function (Blueprint $table) {
            $table->id();
            $table->string('business_id');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('gender');
            $table->dateTime('birth_date');
            $table->string('national_id');
            $table->string('nationality');
            $table->string('country');
            $table->string('city_name');
            $table->string('zip_code');
            $table->string('first_line');
            $table->string('second_line');
            $table->string('third_line');
            $table->string('email');
            $table->string('mob_phone');
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
        Schema::dropIfExists('business_directors');
    }
}
