<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_agents', function (Blueprint $table) {
            $table->id();
			$table->string('tin_no');
			$table->string('plot_no');
			$table->string('block');
			$table->string('town');
			$table->string('region');
			$table->string('reference_no', '35');
			$table->boolean('is_verified')->default(false);
			$table->boolean('is_paid')->default(false);
			$table->unsignedBigInteger('taxpayer_id');
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
        Schema::dropIfExists('tax_agents');
    }
}
