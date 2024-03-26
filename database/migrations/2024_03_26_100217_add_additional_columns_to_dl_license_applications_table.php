<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalColumnsToDlLicenseApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dl_license_applications', function (Blueprint $table) {
            // Drop columns that are no longer needed
            $table->dropColumn(['taxpayer_id','dl_application_status_id', 'dl_blood_group_id', 'dl_license_duration_id', 'dob', 'confirmation_number', 'photo_path']);
            
            // Add new columns
            $table->unsignedBigInteger('driving_school_id');
            $table->unsignedBigInteger('license_duration_id');
            $table->unsignedBigInteger('license_duration');
            $table->longText('completion_certificate_path')->nullable();
            $table->longText('lost_report')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('status', 255)->nullable();
            
            // Modify existing column to match new schema
            $table->unsignedBigInteger('dl_drivers_license_owner_id')->change();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dl_license_applications', function (Blueprint $table) {
            // Revert changes made in the up() method if needed
            $table->unsignedBigInteger('taxpayer_id');
            $table->unsignedBigInteger('dl_blood_group_id');
            $table->unsignedBigInteger('dl_license_duration_id');
            $table->date('dob');
            $table->string('competence_number', 50)->nullable();
            $table->string('certificate_number', 50)->nullable();
            $table->string('confirmation_number', 50)->nullable();
            $table->string('photo_path', 100)->nullable();
            $table->dropColumn(['driving_school_id', 'license_duration_id', 'license_duration', 'completion_certificate', 'lost_report', 'payment_status', 'status']);
            
            // Modify existing column to match previous schema
            $table->unsignedBigInteger('dl_drivers_license_owner_id')->change();
            
            // Drop foreign key constraint
            $table->dropForeign(['dl_drivers_license_owner_id']);
        });
    }
}
