<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrBlacklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_blacklists', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('motor-vehicle or drivers-license');
            $table->string('blacklist_type');
            $table->string('initiator_type');
            $table->unsignedBigInteger('blacklist_id');
            $table->unsignedBigInteger('created_by');
            $table->string('evidence_path')->nullable();
            $table->string('reasons')->nullable();
            $table->string('status')->nullable();
            $table->string('marking')->nullable();
            $table->boolean('is_blocking')->default(false);
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
        Schema::dropIfExists('mvr_blacklists');
    }
}
