<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxClaimAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_claim_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('claim_id');
            $table->decimal('principal_amount', 20, 2)->nullable();
            $table->decimal('interest_amount', 20, 2)->nullable();
            $table->decimal('penalty_amount', 20, 2)->nullable();
            $table->string('report_path')->nullable();
            $table->enum('status', ['submitted', 'control-number-generating', 'control-number-generated', 'control-number-generating-failed', 'paid-partially', 'complete'])->default('submitted');

            $table->softDeletes();
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
        Schema::dropIfExists('tax_claim_assessments');
    }
}
