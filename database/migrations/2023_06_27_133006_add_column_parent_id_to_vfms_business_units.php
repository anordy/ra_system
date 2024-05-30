<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnParentIdToVfmsBusinessUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vfms_business_units', function (Blueprint $table) {
            //
            $table->string('parent_id')->after('unit_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vfms_business_units', function (Blueprint $table) {
            //
            $table->dropColumn('parent_id');
        });
    }
}
