<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCodeToSystemSettingCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_setting_categories', function (Blueprint $table) {
            //
            $table->string('code')->after('name')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_setting_categories', function (Blueprint $table) {
            //
            $table->dropColumn('code');
        });
    }
}
