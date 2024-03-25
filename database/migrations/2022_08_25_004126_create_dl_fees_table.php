<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDlFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dl_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount',15);
            $table->enum('type',['FRESH','DUPLICATE', 'RENEW']);
            $table->string('gfs_code')->default(116101);
            $table->integer('duration')->nullable();
            $table->unsignedBigInteger('DL_LICENSE_DURATION_ID');
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
        Schema::dropIfExists('dl_fees');
    }
}
