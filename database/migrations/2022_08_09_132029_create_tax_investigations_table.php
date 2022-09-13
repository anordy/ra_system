<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_investigations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->string('intension')->nullable();
            $table->text('scope')->nullable();
            $table->date('period_from')->nullable();
            $table->date('period_to')->nullable();
            $table->date('initiated_date')->nullable();
            $table->string('investigation_report')->nullable();
            $table->string('working_report')->nullable();
            $table->string('created_by_type');
            $table->unsignedBigInteger('created_by_id');
            $table->enum('origin', ['system', 'manual'])->default('system');
            $table->enum('status', ['draft', 'pending', 'approved', 'correction', 'legal'])->default('draft');
            $table->string('marking')->nullable();
            $table->dateTime('approved_on')->nullable();
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
        Schema::dropIfExists('tax_investigations');
    }
}
