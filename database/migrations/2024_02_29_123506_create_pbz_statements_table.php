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
            $table->string('account_name')->nullable();
            $table->string('currency');
            $table->string('credttm')->nullable();
            $table->string('stmdt')->nullable();
            $table->string('opencdtdbtind')->nullable();
            $table->string('openbal')->nullable();
            $table->string('closecdtdbtind')->nullable();
            $table->string('closebal')->nullable();
            $table->string('nboftxs')->nullable();
            $table->string('orgmsgid')->nullable();
            $table->string('status')->nullable();
            $table->string('error')->nullable();
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
        Schema::dropIfExists('pbz_statements');
    }
}
