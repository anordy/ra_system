<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatesTableTaPaymentConfigurationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('ta_payment_configuration_history', function (Blueprint $table){
		    $table->id();
		    $table->enum('category', ['Registration Fee', 'Renewal Fee']);
			$table->bigInteger('tapc_id');
		    $table->string('duration')->nullable();
		    $table->integer('is_citizen');
		    $table->decimal('amount', 20,2);
            $table->enum('currency', ['TZS','USD']);
		    $table->bigInteger('created_by');
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
        //
    }
}
