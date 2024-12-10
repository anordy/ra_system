<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNtrElectronicVatReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ntr_electronic_vat_return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('config_id');
            $table->decimal('value',20, 2);
            $table->decimal('vat',20, 2);
            $table->string('rate_type',20, 2)->nullable();
            $table->string('currency',20, 2)->nullable();
            $table->decimal('rate',20, 2)->nullable();
            $table->unsignedBigInteger('return_id');
            $table->unsignedBigInteger('business_id');
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
        Schema::dropIfExists('ntr_electronic_vat_return_items');
    }
}
