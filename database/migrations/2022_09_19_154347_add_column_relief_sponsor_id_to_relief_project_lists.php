<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnReliefSponsorIdToReliefProjectLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relief_project_lists', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('relief_sponsor_id')->after('ministry_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relief_project_lists', function (Blueprint $table) {
            //
        });
    }
}
