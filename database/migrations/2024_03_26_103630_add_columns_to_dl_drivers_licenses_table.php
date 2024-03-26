<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToDlDriversLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dl_drivers_licenses', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['dl_license_duration_id']);
            $table->dropForeign(['dl_license_application_id']);
            $table->dropColumn(['dl_license_duration_id', 'dl_license_application_id']);
            
            // Add new columns
            $table->unsignedBigInteger('taxpayer_id')->after('dl_drivers_license_owner_id');
            $table->unsignedBigInteger('license_duration')->after('taxpayer_id');
            
            // Add foreign key constraint
            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dl_drivers_licenses', function (Blueprint $table) {
            // Revert changes made in the up() method if needed
            $table->unsignedBigInteger('dl_license_duration_id');
            $table->unsignedBigInteger('dl_license_application_id');
            $table->dropColumn(['taxpayer_id', 'license_duration']);
            
            // Add back foreign key constraints
            $table->foreign('dl_license_duration_id')->references('id')->on('dl_license_durations');
            $table->foreign('dl_license_application_id')->references('id')->on('dl_license_applications');
        });
    }
}

