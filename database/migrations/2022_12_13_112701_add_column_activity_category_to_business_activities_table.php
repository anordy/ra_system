<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnActivityCategoryToBusinessActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_activities', function (Blueprint $table) {
            //
            $table->string('activity_category')->after('name')->default('others');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_activities', function (Blueprint $table) {
            //
            $table->dropColumn('activity_category');
        });
    }
}
