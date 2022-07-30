<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('port_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id');
            $table->string('return_month_id');
            $table->string('taxtype_code');
            $table->decimal('total_input_tax', 40, 2);
            $table->decimal('total_vat_payable_tzs', 40, 2);
            $table->decimal('total_vat_payable_usd', 40, 2);
            $table->decimal('infrastructure', 40, 2);
            $table->decimal('infrastructure_znz_znz', 40, 2);
            $table->decimal('infrastructure_znz_tm', 40, 2);
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('port_returns');
    }
}
