<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSubTaxIdToBusinessTaxTypeChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_tax_type_changes', function (Blueprint $table) {
            $table->unsignedBigInteger('to_sub_vat_id')->nullable()->after('to_tax_type_id');
        });
    }

}
