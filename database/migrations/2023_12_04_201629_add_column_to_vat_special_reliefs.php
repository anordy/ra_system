<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToVatSpecialReliefs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vat_special_reliefs', function (Blueprint $table) {
            //
            $table->decimal('amount_after_relief', 20, 2)->nullable()->after('relief_number');
            $table->decimal('tax_after_relief', 20, 2)->nullable()->after('relief_number');
            $table->decimal('relieved_amount', 20, 2)->nullable()->after('relief_number');
            $table->string('currency')->nullable()->after('relief_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vat_special_reliefs', function (Blueprint $table) {
            //
            $table->dropColumn('amount_after_relief');
            $table->dropColumn('tax_after_relief');
            $table->dropColumn('relieved_amount');
            $table->dropColumn('currency');
        });
    }
}
