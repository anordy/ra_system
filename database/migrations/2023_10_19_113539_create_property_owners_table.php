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
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->timestamp('date_of_birth')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->unsignedBigInteger('id_type');
            $table->string('id_number');
            $table->timestamp('id_verified_at')->nullable();

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
