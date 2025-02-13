<?php

use Illuminate\Database\Migrations\Migration;

class CreateDrivingLicenseReportView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE VIEW view_driving_licenses AS
          SELECT  ROW_NUMBER() OVER (ORDER BY DDL.ID) AS S_NO,
       DDL.LICENSE_NUMBER,
       DLA.TYPE,
       T.FIRST_NAME ||' '|| T.LAST_NAME AS FULL_NAME,
       T.MOBILE,
       DDL.ISSUED_DATE,
       DDL.EXPIRY_DATE,
       DLD.DESCRIPTION AS DURATION,
       DLA.LICENSE_DURATION_ID,
       DDL.IS_BLOCKED,
       DLA.PAYMENT_STATUS,
       R.LOCATION,
       DDL.STATUS
FROM DL_DRIVERS_LICENSES DDL
         JOIN DL_LICENSE_APPLICATIONS DLA
              ON DDL.DL_LICENSE_APPLICATION_ID = DLA.ID
         JOIN DL_LICENSE_DURATIONS DLD
              ON DLA.LICENSE_DURATION_ID = DLD.ID
         JOIN TAXPAYERS T
              ON DDL.TAXPAYER_ID = T.ID
         JOIN REGIONS R
              ON T.REGION_ID = R.ID
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW view_driving_licenses");
    }
}
