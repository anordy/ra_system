<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ra_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ra_incident_id');
            $table->enum('type', ['Revenue Loss', 'Overcharging']); // Identifies the type of issue
            $table->decimal('detected', 18, 2)->nullable();
            $table->decimal('prevented', 18, 2)->nullable();
            $table->decimal('recovered', 18, 2)->nullable();
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
        Schema::dropIfExists('ra_issues');
    }
}
