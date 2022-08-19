<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrTransferFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_transfer_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->decimal('amount',10);
            $table->string('gfs_code',15);
            $table->unsignedBigInteger('mvr_transfer_category_id');
            $table->timestamps();

            $table->foreign('mvr_transfer_category_id')->references('id')->on('mvr_transfer_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mvr_trasfer_fees');
    }
}
