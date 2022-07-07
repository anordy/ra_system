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
			$table->string('reference_no', '35')->nullable();
			$table->enum('status', ['drafting','pending', 'approved', 'rejected', 'completed'])->default('drafting');
			$table->boolean('is_paid')->default(false);
			$table->enum('is_first_application', [1,0])->default(1);
	        $table->dateTime('app_first_date')->nullable();
	        $table->dateTime('app_expire_date')->nullable();
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
