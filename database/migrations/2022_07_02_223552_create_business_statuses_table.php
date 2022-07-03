<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id')->unique();
            $table->enum('status', ['open','closed']);
            $table->timestamps();

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
        Schema::dropIfExists('business_statuses');
    }
}
