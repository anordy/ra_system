<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeRegistrationsIdToMvrDeregistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mvr_deregistrations', function (Blueprint $table) {
            $table->unsignedBigInteger('de_registration_certificate_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mvr_deregistrations', function (Blueprint $table) {
            $table->dropColumn('de_registration_certificate_id');
        });
    }
}
