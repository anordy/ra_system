<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessConsultantsTable extends Migration
{

    public function up()
    {
        Schema::create('business_consultants', function (Blueprint $table) {
	        $table->id();
	        $table->unsignedInteger('business_id');
	        $table->unsignedInteger('taxpayer_id');
	        $table->timestamp('reviewed_at')->nullable();
	        $table->enum('status', ['pending', 'approved', 'rejected', 'removed'])->default('pending');
			$table->timestamp('removed_at')->nullable();
	        $table->string('remarks')->nullable();
            $table->string('contract')->nullable();
            $table->softDeletes();
	        $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_consultants');
    }
}
