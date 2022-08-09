<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxpayer_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->decimal('amount_due',40,2);
            $table->decimal('amount_due_with_penalties',40,2);
            $table->string('currency');
            $table->enum('status',['unpaid','waiver','overdue','control-number-generating','control-number-generated','control-number-generating-failed','paid-partially','complete']);
            $table->date('due_date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
            $table->foreign('location_id')->references('id')->on('business_locations');
            $table->foreign('tax_type_id')->references('id')->on('tax_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debts');
    }
}
