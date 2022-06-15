<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_owners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_payer_id');
            $table->unsignedBigInteger('business_id');
            $table->string('position');
            $table->boolean('is_responsible_person')->default(0);
            $table->timestamps();

            $table->foreign('tax_payer_id')->references('id')->on('tax_payers');
            $table->foreign('business_id')->references('id')->on('businesses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_owners');
    }
}
