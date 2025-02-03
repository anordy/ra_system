<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrRegistrationAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_registration_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attachment_type_id')->nullable();
            $table->unsignedBigInteger('mvr_registration_id');
            $table->string('name');
            $table->string('path');
            $table->timestamps();
        });
    }

    /**pa
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mvr_registration_attachments');
    }
}
