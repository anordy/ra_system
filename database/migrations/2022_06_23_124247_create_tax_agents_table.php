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
			$table->unsignedBigInteger('district_id');
			$table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('ward_id');
			$table->string('reference_no', '35')->nullable();
			$table->string('emp_status');
			$table->string('emp_letter')->nullable();
			$table->string('passport_photo')->nullable();
			$table->string('approval_letter')->nullable();
			$table->string('cv')->nullable();
			$table->string('tin_certificate')->nullable();
			$table->enum('status', ['drafting','pending', 'approved', 'rejected', 'completed', 'verified','correction'])->default('drafting');
            $table->enum('billing_status', ['control-number-generating','control-number-generated', 'control-number-generating-failed', 'paid-partially', 'complete'])->nullable();
			$table->boolean('is_paid')->default(false);
			$table->enum('is_first_application', [1,0])->default(1);
			$table->string('has_professional')->nullable();
            $table->string('has_training')->nullable();
	        $table->timestamp('app_first_date')->nullable();
	        $table->timestamp('app_expire_date')->nullable();
			$table->unsignedBigInteger('taxpayer_id');
			$table->string('verifier_true_comment')->nullable();
            $table->string('app_true_comment')->nullable();
            $table->string('verifier_reject_comment')->nullable();
            $table->string('app_reject_comment')->nullable();
            $table->string('marking')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('first_rejected_at')->nullable();
            $table->timestamp('final_rejected_at')->nullable();
            $table->unsignedBigInteger('verifier_id')->nullable();
            $table->unsignedBigInteger('approver_id')->nullable();
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
