<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_owners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id')->index('property_owner_property_id_index');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('gender');
            $table->timestamp('date_of_birth');
            $table->string('mobile');
            $table->string('email');
            $table->string('address');
            $table->unsignedBigInteger('id_type');
            $table->string('id_number');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_owners');
    }
}
