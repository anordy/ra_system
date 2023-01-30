<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSubVatIdToBusinessTaxTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_tax_type', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_vat_id')->nullable()->after('tax_type_id');
        });
    }
}
