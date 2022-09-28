<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnOnDlLicenseApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dl_license_applications', function(Blueprint $table) {
            $table->renameColumn('competence_number','certificate_path');
        });

        Schema::table('dl_license_applications', function(Blueprint $table) {
            $table->string('certificate_path',100)->nullable(true)->change();
        });

        Schema::table('dl_drivers_license_owners', function(Blueprint $table) {
            $table->renameColumn('competence_number','certificate_path');
        });

        Schema::table('dl_drivers_license_owners', function(Blueprint $table) {
            $table->string('certificate_path',100)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
