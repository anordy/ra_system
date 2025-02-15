<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrDeregistrationAttachmentFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_deregistration_attachment_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attachment_type_id')->nullable();
            $table->unsignedBigInteger('mvr_deregistration_id');
            $table->string('name');
            $table->string('path');
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
        Schema::dropIfExists('mvr_deregistration_attachment_files');
    }
}
