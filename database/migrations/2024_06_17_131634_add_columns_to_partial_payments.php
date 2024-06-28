<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPartialPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partial_payments', function (Blueprint $table) {
            $table->string('currency')->nullable();
            $table->date('due_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partial_payments', function (Blueprint $table) {
            $table->dropColumn('currency');
            $table->dropColumn('due_date');
        });
    }
}
