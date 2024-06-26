<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrTemporaryTransportFileTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_temporary_transport_file_types', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('file_type');
            $table->boolean('is_required');
            $table->boolean('is_approved');
            $table->boolean('is_updated');
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
        Schema::dropIfExists('mvr_temporary_transport_file_types');
    }
}
