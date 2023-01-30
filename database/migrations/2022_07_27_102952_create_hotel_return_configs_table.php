<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelReturnConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_return_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('financia_year_id');
            $table->integer('order')->default(0);
            $table->string('code');
            $table->string('name');
            $table->string('tax_type_code')->nullable();
            $table->enum('row_type',['dynamic', 'unremovable'])->default('dynamic');
            $table->enum('heading_type',['supplies', 'purchases'])->nullable();
            $table->boolean('value_calculated')->default(false);
            $table->enum('col_type',['total', 'subtotal','normal', 'hotel_top', 'hotel_bottom'])->default('normal');
            $table->boolean('rate_applicable')->default(true);
            $table->enum('rate_type', ['percentage', 'fixed'])->nullable();
            $table->enum('currency',['TZS', 'USD', 'BOTH'])->default('TZS');
            $table->decimal('rate')->unsigned()->default(0);
            $table->decimal('rate_usd')->nullable();
            $table->string('formular')->nullable();
            $table->string('value_formular')->nullable();
            $table->boolean('active')->default(false);

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
        Schema::dropIfExists('hotel_return_configs');
    }
}
