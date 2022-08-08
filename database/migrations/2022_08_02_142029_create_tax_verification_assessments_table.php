<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxVerificationAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_verification_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('verification_id');
            $table->decimal('principal_amount', 20, 2);
            $table->decimal('interest_amount', 20, 2);
            $table->decimal('penalty_amount', 20, 2);
            $table->string('report_path')->nullable();
            $table->enum('status',['submitted', 'control-number-generating', 'control-number-generated', 'control-number-generating-failed', 'paid-partially', 'complete'])->default('submitted');
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
        Schema::dropIfExists('tax_verification_assessments');
    }
}
