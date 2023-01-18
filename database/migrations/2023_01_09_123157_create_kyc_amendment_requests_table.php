<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKycAmendmentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kyc_amendment_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('kyc_id');
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
        Schema::dropIfExists('kyc_amendment_requests');
    }
}
