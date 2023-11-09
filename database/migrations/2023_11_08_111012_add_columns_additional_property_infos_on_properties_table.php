<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsAdditionalPropertyInfosOnPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->unsignedBigInteger('ownership_type_id')->nullable();
            $table->string('institution_name')->nullable();
            $table->string('size')->nullable();
            $table->string('features')->nullable();
            $table->string('property_value')->nullable();
            $table->string('purchase_value')->nullable();
            $table->timestamp('acquisition_date')->nullable();
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
