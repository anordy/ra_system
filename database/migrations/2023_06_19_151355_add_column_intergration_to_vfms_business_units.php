<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIntergrationToVfmsBusinessUnits extends Migration
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
            $table->boolean('integration')->default(false)->after('is_headquarter');
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
            $table->dropColumn('integration');
        });
    }
}
