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
			$table->string('district_id');
			$table->string('region_id');
			$table->string('reference_no', '35')->nullable();
			$table->string('emp_status');
			$table->text('emp_letter')->nullable();
			$table->text('passport_photo')->nullable();
			$table->text('cv')->nullable();
			$table->text('tin_certificate')->nullable();
			$table->enum('status', ['drafting','pending', 'approved', 'rejected', 'completed', 'verified'])->default('drafting');
			$table->boolean('is_paid')->default(false);
			$table->enum('is_first_application', [1,0])->default(1);
	        $table->dateTime('app_first_date')->nullable();
	        $table->dateTime('app_expire_date')->nullable();
			$table->unsignedBigInteger('taxpayer_id');
			$table->text('verifier_true_comment')->nullable();
            $table->text('verifier_reject_comment')->nullable();
            $table->text('verifier_reject_comment')->nullable();
            $table->text('app_reject_comment')->nullable();
            $table->unsignedBigInteger('verifier_id');
            $table->unsignedBigInteger('approver_id');
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
