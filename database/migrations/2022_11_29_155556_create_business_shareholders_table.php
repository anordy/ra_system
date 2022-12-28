<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessShareholdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_shareholders', function (Blueprint $table) {
            $table->id();
            $table->string('business_id');
            $table->string('entity_name');
            $table->timestamp('birth_date');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('country');
            $table->string('national_id');
            $table->string('nationality');
            $table->string('city_name');
            $table->string('zip_code');
            $table->string('first_line');
            $table->string('second_line');
            $table->string('third_line');
            $table->string('email');
            $table->string('mob_phone');
            $table->string('full_address');
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
        Schema::dropIfExists('business_shareholders');
    }
}
