<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBillUpdatedToPropertyPaymentExtensions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_payment_extensions', function (Blueprint $table) {
            //
            $table->boolean('bill_updated')->default(true)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_payment_extensions', function (Blueprint $table) {
            //
            $table->dropColumn('bill_updated');
        });
    }
}
