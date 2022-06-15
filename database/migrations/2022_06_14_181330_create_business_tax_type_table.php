<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessTaxTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_tax_type', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id');
            $table->bigInteger('tax_type_id');
            $table->enum('currency',['TZS','USD']);

            $table->foreign('business_id')->references('id')->on('businesses');
            $table->foreign('tax_type_id')->references('id')->on('tax_types');
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
        Schema::dropIfExists('business_tax_type');
    }
}
