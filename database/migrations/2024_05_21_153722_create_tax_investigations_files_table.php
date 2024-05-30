<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxInvestigationsFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_investigations_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_investigation_id');
            $table->string('name');
            $table->string('path');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('tax_investigation_id')->references('id')->on('tax_investigations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_investigations_files');
    }
}
