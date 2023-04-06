<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZmEgaChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zm_ega_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zm_bill_id');
            $table->enum('currency', ['TZS', 'USD', 'EUR']);
            $table->decimal('amount', 38, 2);
            $table->boolean('ega_charges_included');
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
        Schema::dropIfExists('zm_ega_charges');
    }
}
