<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxpayer_id')->nullable();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('id_type')->nullable();
            $table->string('id_number')->nullable();
            $table->string('name');
            $table->string('mobile');
            $table->string('currency');
            $table->decimal('amount', 15, 2);
            $table->integer('tax_type');
            $table->unsignedBigInteger('sub_vat_id')->nullable();
            $table->string('status');
            $table->string('receipt_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offences');
    }
}
