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
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('taxpayer_id');
            $table->longText('old_taxtype')->nullable();
            $table->longText('new_taxtype')->nullable();
            $table->string('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('marking')->nullable();
            $table->dateTime('approved_on')->nullable();
            $table->foreign('business_id')->references('id')->on('businesses');
            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
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
