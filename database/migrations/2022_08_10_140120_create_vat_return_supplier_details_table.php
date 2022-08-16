<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVatReturnSupplierDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vat_return_supplier_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vat_return_id');
            $table->string('taxpayer_zin_number');
            $table->string('supplier_zin_number');
            $table->string('tax_invoice_number');
            $table->dateTime('date_of_tax_invoice');
            $table->string('release_number');
            $table->decimal('value',20,2);
            $table->decimal('vat',20,2);
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
        Schema::dropIfExists('vat_return_supplier_details');
    }
}
