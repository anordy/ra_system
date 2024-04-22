<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToDlDriversLicenseOwnersTable extends Migration
{
    public function up()
    {
        Schema::table('dl_drivers_license_owners', function (Blueprint $table) {
            $table->unsignedBigInteger('taxpayer_id')->nullable()->change();
            $table->string('passport_attachment')->nullable();
            $table->string('nida_attachment')->nullable();
            $table->string('gender')->nullable();
            $table->bigInteger('zan_id')->nullable()->unique();
            $table->string('ref_no')->nullable()->unique();
            $table->string('passport_no')->nullable()->unique();
            $table->bigInteger('nida_no')->nullable()->unique();
        });
    }

    public function down()
    {
        Schema::table('dl_drivers_license_owners', function (Blueprint $table) {
            $table->dropColumn('passport_attachment');
            $table->dropColumn('nida_attachment');
            $table->string('gender')->nullable();
            $table->dropColumn('zan_id');
            $table->dropColumn('passport_no');
            $table->dropColumn('ref_no');
            $table->dropColumn('nida_no');
        });
    }
}
