<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessTaxTypeChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_tax_type_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_tax_type_id');
            $table->string('old_taxtype');
            $table->string('new_taxtype');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->foreign('business_tax_type_id')->references('id')->on('business_tax_type');
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
        Schema::dropIfExists('business_tax_type_changes');
    }
}
