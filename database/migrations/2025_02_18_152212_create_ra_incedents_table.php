<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaIncedentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ra_incedents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference');
            $table->unsignedBigInteger('bank_channel_id');
            $table->string('name');
            $table->boolean('real_issue')->default(true);            $table->text('symptom_of_incident');
            $table->decimal('impact_potential_revenue', 18, 2)->nullable();
            $table->integer('impact_potential_customers')->nullable();
            $table->string('impact_potential_system')->nullable();
            $table->date('incident_reported_date');
            $table->string('status');
            $table->unsignedBigInteger('reported_by');
            $table->string('problem_owner');
            $table->text('affected_rev_stream')->comment('Affected revenue stream or system');
            $table->text('action');
            $table->text('root_cause_analysis');
            $table->text('others');
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
        Schema::dropIfExists('ra_incedents');
    }
}
