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
            $table->string('type');
            $table->decimal('amount_due',40,2);
            $table->string('curency');
            $table->enum('status',['paid','unpaid','waiver']);
            $table->date('due_date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
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
