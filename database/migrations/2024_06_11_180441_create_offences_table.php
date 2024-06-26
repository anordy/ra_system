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
//            $table->unsignedBigInteger('taxpayer_id');
            $table->string('name');
            $table->string('mobile');
            $table->string('currency');
            $table->decimal('amount', 15, 2);
            $table->integer('tax_type');
            $table->string('status');
            $table->string('receipt_number')->nullable();
            $table->timestamps();
            $table->softDeletes();

//            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');

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
