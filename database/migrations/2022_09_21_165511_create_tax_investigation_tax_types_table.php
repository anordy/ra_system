<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxInvestigationTaxTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_investigation_tax_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_investigation_id');
            $table->unsignedBigInteger('business_tax_type_id');
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
        Schema::dropIfExists('tax_investigation_tax_types');
    }
}
