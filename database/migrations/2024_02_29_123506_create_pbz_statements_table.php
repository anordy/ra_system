<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePbzStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pbz_statements', function (Blueprint $table) {
            $table->id();
            $table->string('account_no');
            $table->string('currency');
            $table->string('credttm');
            $table->string('stmdt');
            $table->string('opencdtdbtind');
            $table->string('openbal');
            $table->string('closecdtdbtind');
            $table->string('closebal');
            $table->string('nboftxs');
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
        Schema::dropIfExists('pbz_statements');
    }
}
