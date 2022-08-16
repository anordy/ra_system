<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxCreditItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_credit_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('credit_id');
            $table->unsignedBigInteger('return_id');
            $table->string('return_type');
            $table->decimal('amount');
            $table->enum('currency', ['TZS', 'USD', 'EUR'])->default('TZS');
            $table->softDeletes();
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
        Schema::dropIfExists('tax_credit_items');
    }
}
