<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxAgentHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_agent_history', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('tax_agent_id');
			$table->dateTime('tax_agent_id');
			$table->dateTime('app_expire_date');
			$table->enum('app_status', ['first', 'renew']);
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
        Schema::dropIfExists('tax_agent_history');
    }
}
