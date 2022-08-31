<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsOnMvrFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mvr_fees', function (Blueprint $table) {
            $table->unsignedBigInteger('mvr_class_id')->nullable();
            $table->enum('status',['ACTIVE','INACTIVE'])->default('ACTIVE');

            $table->foreign('mvr_class_id')->references('id')->on('mvr_classes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
