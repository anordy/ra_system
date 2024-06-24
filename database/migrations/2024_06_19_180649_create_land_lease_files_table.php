<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandLeaseFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('land_lease_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('land_lease_id');
            $table->string('name');
            $table->string('previous_file_path')->nullable();
            $table->string('file_path');
            $table->string('approval_status');
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
        Schema::dropIfExists('land_lease_files');
    }
}
