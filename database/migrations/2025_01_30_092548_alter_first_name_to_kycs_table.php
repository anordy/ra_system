<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFirstNameToKycsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kycs', function (Blueprint $table) {
            $table->string('artificial_name')->nullable();
            $table->string('bpra_number')->nullable();
            $table->string('designation')->nullable();
            $table->string('resp_name')->nullable();
            $table->string('resp_mobile')->nullable();
            $table->string('resp_email')->nullable();
            $table->string('reg_type')->nullable();
            $table->string('service_type')->nullable();
            $table->string('ownership_type')->nullable();
            $table->boolean('is_temp_reg')->default(0);
            $table->string('first_name')->change()->nullable();
            $table->string('last_name')->change()->nullable();
            $table->unsignedBigInteger('id_type')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kycs', function (Blueprint $table) {
            //
        });
    }
}
