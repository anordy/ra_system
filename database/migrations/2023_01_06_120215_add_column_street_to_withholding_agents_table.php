<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStreetToWithholdingAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withholding_agents', function (Blueprint $table) {
            $table->unsignedBigInteger('street_id')->nullable()->after('ward_id');
        });
    }

}
