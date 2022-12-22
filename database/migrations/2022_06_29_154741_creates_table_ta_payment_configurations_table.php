<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatesTableTaPaymentConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ta_payment_configurations', function (Blueprint $table){
	        $table->id();
	        $table->enum('category', ['Registration Fee', 'Renewal Fee']);
			$table->string('duration')->nullable();
			$table->integer('is_citizen');
			$table->decimal('amount', 20,2);
			$table->enum('currency',['TZS','USD']);
			$table->bigInteger('created_by');
            $table->integer('is_approved')->nullable(0);
	        $table->timestamps();
        });
    }

    public function down()
    {
	    Schema::dropIfExists('ta_payment_configurations');
    }
}
