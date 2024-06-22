<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePbzReversalStatementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pbz_reversal_statement', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pbz_reversal_id');
            $table->unsignedBigInteger('pbz_statement_id');
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
        Schema::dropIfExists('pbz_reversal_statement');
    }
}
