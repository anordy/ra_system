<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenewTaxAgentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renew_tax_agent_requests', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('tax_agent_id');
            $table->boolean('is_paid')->default(false);
            $table->timestamp('renew_first_date')->nullable();
            $table->timestamp('renew_expire_date')->nullable();
			$table->enum('status', ['pending', 'verified', 'rejected','approved'])->default('pending');
            $table->enum('billing_status', ['control-number-generating','control-number-generated', 'control-number-generating-failed', 'paid-partially', 'complete'])->nullable();
            $table->string('app_true_comment')->nullable();
            $table->string('app_reject_comment')->nullable();
            $table->unsignedBigInteger('approved_by_id')->nullable();
            $table->unsignedBigInteger('rejected_by_id')->nullable();
            $table->string('marking')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
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
        Schema::dropIfExists('renew_tax_agent_requests');
    }
}
