<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNullablesToBusinessTaxTypeChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_tax_type_changes', function (Blueprint $table) {
            $table->unsignedBigInteger('to_tax_type_id')->nullable()->change();
            $table->string('from_tax_type_currency')->nullable()->change();
            $table->string('to_tax_type_currency')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_tax_type_changes', function (Blueprint $table) {
            //
        });
    }
}
