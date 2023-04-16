<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToVatReturnSupplierDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vat_return_supplier_details', function (Blueprint $table) {
            $table->enum('supply_type',['fifteen_percent', 'eighteen_percent'])->after('vat')->nullable();
            $table->unsignedBigInteger('business_location_id')->after('vat_return_id')->nullable();
            $table->unsignedBigInteger('financial_month_id')->after('business_location_id')->nullable();
            $table->string('vat_registration_number')->after('business_location_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vat_return_supplier_details', function (Blueprint $table) {
            //
        });
    }
}
