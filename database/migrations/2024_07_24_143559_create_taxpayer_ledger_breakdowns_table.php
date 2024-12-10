<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxpayerLedgerBreakdownsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxpayer_ledger_breakdowns', function (Blueprint $table) {
            $table->id();
            $table->decimal('current_principal', 20, 2)->default(0);
            $table->decimal('current_interest', 20, 2)->default(0);
            $table->decimal('current_penalty', 20, 2)->default(0);
            $table->decimal('current_infrastructure', 20, 2)->default(0);
            $table->decimal('current_airport_safety_fee', 20, 2)->default(0);
            $table->decimal('current_airport_service_charge', 20, 2)->default(0);
            $table->decimal('current_seaport_service_charge', 20, 2)->default(0);
            $table->decimal('current_seaport_transport_charge', 20, 2)->default(0);
            $table->decimal('current_infrastructure_znz_znz', 20, 2)->default(0);
            $table->decimal('current_infrastructure_znz_tz', 20, 2)->default(0);
            $table->decimal('current_rdf_fee', 20, 2)->default(0);
            $table->decimal('current_road_license_fee', 20, 2)->default(0);
            $table->decimal('current_total_amount', 20, 2)->default(0);
            
            $table->decimal('principal', 20, 2)->default(0);
            $table->decimal('interest', 20, 2)->default(0);
            $table->decimal('penalty', 20, 2)->default(0);
            $table->decimal('infrastructure', 20, 2)->default(0);
            $table->decimal('airport_safety_fee', 20, 2)->default(0);
            $table->decimal('airport_service_charge', 20, 2)->default(0);
            $table->decimal('seaport_service_charge', 20, 2)->default(0);
            $table->decimal('seaport_transport_charge', 20, 2)->default(0);
            $table->decimal('infrastructure_znz_znz', 20, 2)->default(0);
            $table->decimal('infrastructure_znz_tz', 20, 2)->default(0);
            $table->decimal('rdf_fee', 20, 2)->default(0);
            $table->decimal('road_license_fee', 20, 2)->default(0);
            $table->decimal('total_amount', 20, 2)->default(0);

            $table->unsignedBigInteger('ledger_id');
            $table->unsignedBigInteger('ledger_payment_id');
            $table->unsignedBigInteger('ledger_payment_item_id');
            $table->string('currency');
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
        Schema::dropIfExists('taxpayer_ledger_breakdowns');
    }
}
