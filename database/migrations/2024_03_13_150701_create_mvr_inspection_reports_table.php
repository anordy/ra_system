<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrInspectionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_inspection_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_path');
            $table->dateTime('inspection_date');
            $table->decimal('inspection_mileage');
            $table->unsignedBigInteger('mvr_registration_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mvr_inspection_reports');
    }
}
