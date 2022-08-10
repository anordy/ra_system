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
            $table->string('supplier_reference_number');
            $table->string('zrb_number');
            $table->string('tax_invoice_number');
            $table->dateTime('date_of_tax_invoice');
            $table->string('release_number');
            $table->decimal('value');
            $table->decimal('vat');
            $table->unsignedBigInteger('filled_by_id');
            $table->string('filled_by_type');
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
