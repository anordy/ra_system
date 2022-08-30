<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsOnMvrRegistrationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mvr_registration_types', function (Blueprint $table) {
            $table->unsignedBigInteger('mvr_registration_type_category_id')
                ->nullable();
            $table->foreign('mvr_registration_type_category_id')->references('id')->on('mvr_registration_type_categories');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
