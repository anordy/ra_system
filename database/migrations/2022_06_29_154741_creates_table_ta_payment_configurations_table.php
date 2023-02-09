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
	        $table->enum('category', ['Registration', 'Renewal']);
			$table->string('duration')->nullable();
			$table->integer('is_citizen');
			$table->bigInteger('created_by');
            $table->boolean('is_approved')->default(0);
            $table->boolean('is_updated')->default(0);
            $table->string('ci_payload', 4000)->nullable();
            $table->boolean('failed_verification')->default(0);
	        $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
	    Schema::dropIfExists('ta_payment_configurations');
    }
}
