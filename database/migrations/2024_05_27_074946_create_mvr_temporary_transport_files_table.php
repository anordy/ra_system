<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrTemporaryTransportFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_temporary_transport_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mvr_temporary_transport_id');
            $table->unsignedBigInteger('name')->nullable();
            $table->unsignedBigInteger('location');
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
        Schema::dropIfExists('mvr_temporary_transport_files');
    }
}
