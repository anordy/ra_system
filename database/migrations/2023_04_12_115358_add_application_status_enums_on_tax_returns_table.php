<?php

use App\Enum\VettingStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApplicationStatusEnumsOnTaxReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
        Schema::table('tax_returns', function (Blueprint $table) {
            $table->enum('vetting_status', VettingStatus::getConstants())->default(VettingStatus::SUBMITTED);
        });

        Schema::table('mm_transfer_returns', function (Blueprint $table) {
            $table->enum('vetting_status', VettingStatus::getConstants())->default(VettingStatus::SUBMITTED);
        });

        Schema::table('stamp_duty_returns', function (Blueprint $table) {
            $table->enum('vetting_status', VettingStatus::getConstants())->default(VettingStatus::SUBMITTED);
        });

        Schema::table('vat_returns', function (Blueprint $table) {
            $table->enum('vetting_status', VettingStatus::getConstants())->default(VettingStatus::SUBMITTED);
        });

        Schema::table('bfo_returns', function (Blueprint $table) {
            $table->enum('vetting_status', VettingStatus::getConstants())->default(VettingStatus::SUBMITTED);
        });

        Schema::table('mno_returns', function (Blueprint $table) {
            $table->enum('vetting_status', VettingStatus::getConstants())->default(VettingStatus::SUBMITTED);
        });

        Schema::table('petroleum_returns', function (Blueprint $table) {
            $table->enum('vetting_status', VettingStatus::getConstants())->default(VettingStatus::SUBMITTED);
        });

        Schema::table('port_returns', function (Blueprint $table) {
            $table->enum('vetting_status', VettingStatus::getConstants())->default(VettingStatus::SUBMITTED);
        });

        Schema::table('hotel_returns', function (Blueprint $table) {
            $table->enum('vetting_status', VettingStatus::getConstants())->default(VettingStatus::SUBMITTED);
        });

        Schema::table('em_transaction_returns', function (Blueprint $table) {
            $table->enum('vetting_status', VettingStatus::getConstants())->default(VettingStatus::SUBMITTED);
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
