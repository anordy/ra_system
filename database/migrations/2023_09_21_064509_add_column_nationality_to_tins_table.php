<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNationalityToTinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tins', function (Blueprint $table) {
            $table->timestamp('registration_date');
            $table->string('nationality');
            $table->string('postal_city');
            $table->string('block_number');
            $table->boolean('is_business_tin');
            $table->boolean('is_entity_tin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tins', function (Blueprint $table) {
            //
        });
    }
}
