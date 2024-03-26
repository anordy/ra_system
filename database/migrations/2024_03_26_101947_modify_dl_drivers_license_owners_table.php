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
            
            $table->string('blood_group', 5)->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('physical_address');
            $table->string('email')->nullable()->unique();
            $table->string('mobile')->unique();
            $table->string('alt_mobile')->nullable();
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
            // Revert changes made in the up() method if needed
            $table->string('competence_number', 50)->after('taxpayer_id');
            $table->string('certificate_number', 50)->after('competence_number');
            $table->string('confirmation_number', 50)->after('certificate_number');
            $table->string('photo_path', 100)->after('confirmation_number');

            $table->dropColumn(['blood_group', 'first_name', 'middle_name', 'last_name', 'physical_address', 'email', 'mobile', 'alt_mobile', 'certificate_path', 'photo_path']);
            
            // Modify existing column to match previous schema
            $table->date('dob')->change();
        });
    }
}
