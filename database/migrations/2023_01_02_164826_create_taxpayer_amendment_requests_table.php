<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxpayerAmendmentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxpayer_amendment_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('taxpayer_id');
            $table->longText('old_values')->nullable();
            $table->longText('new_values');
			$table->enum('status', ['pending', 'tempered', 'rejected','approved'])->default('pending');
            $table->string('app_true_comment')->nullable();
            $table->string('app_reject_comment')->nullable();
            $table->unsignedBigInteger('approved_by_id')->nullable();
            $table->unsignedBigInteger('rejected_by_id')->nullable();
            $table->string('marking')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
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
        Schema::dropIfExists('taxpayer_amendment_requests');
    }
}
