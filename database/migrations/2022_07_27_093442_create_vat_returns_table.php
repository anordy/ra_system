<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVatReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vat_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxpayer_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->string('return_month');
            $table->string('financial_year');
            $table->string('taxtype_code');
            $table->decimal('standard_rated_supplies', 40,2);
            $table->decimal('zero_rated_supplies', 40,2);
            $table->decimal('exempt_supplies', 40,2);
            $table->decimal('special_relief', 40,2);
            $table->decimal('exempt_import_purchases', 40,2);
            $table->decimal('standard_local_purchases', 40,2);
            $table->decimal('standard_rated_imports', 40,2);
            $table->decimal('total_input_tax', 40,2);
            $table->decimal('total_vat_payable', 40,2);
            $table->decimal('vat_withheld', 40,2);
            $table->decimal('vat_credit_brought_forward', 40,2);
            $table->decimal('infrastructure_tax', 40,2);
            $table->decimal('total_vat_amount_due', 40,2);
            $table->string('has_exemption');
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('vat_returns');
    }
}
