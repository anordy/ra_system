<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDlDriversLicenseOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dl_drivers_license_owners', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['dl_blood_group_id']);

            // Drop columns
            $table->dropColumn('competence_number');
            $table->dropColumn('confirmation_number');
            $table->dropColumn('dl_blood_group_id');

            // Add new columns
            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique();
            $table->string('alt_mobile')->nullable();
            $table->string('certificate_number',50)->nullable();
            $table->longText('certificate_path')->nullable();
            $table->longText('photo_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dl_drivers_license_owners', function (Blueprint $table) {
            // Re-add the foreign key constraint if needed
            $table->foreign('dl_blood_group_id')->references('id')->on('dl_blood_groups');

            // Add back the dropped columns
            $table->dropColumn('competence_number');
            $table->dropColumn('confirmation_number');
            $table->dropColumn('dl_blood_group_id');

            // Drop the newly added columns
            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique();
            $table->string('alt_mobile')->nullable();
            $table->string('certificate_number',50)->nullable();
            $table->longText('certificate_path')->nullable();
            $table->longText('photo_path')->nullable();
        });
    }
}
