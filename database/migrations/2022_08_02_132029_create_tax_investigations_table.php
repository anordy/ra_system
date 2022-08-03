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
            $table->unsignedBigInteger('responsible_person_id');
            $table->date('issue_date');
            $table->text('scope');
            $table->integer('period_covered');
            $table->dateTime('audit_date_time');
            $table->unsignedBigInteger('created_by');
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
